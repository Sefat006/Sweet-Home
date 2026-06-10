<?php

namespace App\Http\Controllers\Back\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Flat;
use App\Models\FlatTenant;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TenantController extends Controller
{
    // ─── Auth guard helpers ───────────────────────────────────────────

    private function getFlat($buildingId, $flatId)
    {
        $building = Building::findOrFail($buildingId);
        if (Auth::user()->role !== 'super_admin' && $building->user_id !== Auth::id()) {
            abort(403);
        }
        return Flat::where('building_id', $building->id)->findOrFail($flatId);
    }

    // ─── 1. Tenant index for a flat ───────────────────────────────────

    public function index($buildingId, $flatId)
    {
        $flat     = $this->getFlat($buildingId, $flatId);
        $building = $flat->building;

        $activeTenant = FlatTenant::with('tenant')
            ->where('flat_id', $flat->id)
            ->where('status', 'active')
            ->first();

        $history = FlatTenant::with('tenant')
            ->where('flat_id', $flat->id)
            ->where('status', 'inactive')
            ->latest()
            ->get();

        return view('admin.tenants.index', compact('building', 'flat', 'activeTenant', 'history'));
    }


    // ─── 2. Enroll tenant — step 1: search existing ───────────────────

    public function enroll($buildingId, $flatId)
    {
        $flat     = $this->getFlat($buildingId, $flatId);
        $building = $flat->building;

        // Block if already has an active tenant
        $hasActive = FlatTenant::where('flat_id', $flat->id)->where('status', 'active')->exists();
        if ($hasActive) {
            return redirect()->route('admin.tenants.index', [$buildingId, $flatId])
                ->with('error', 'This flat already has an active tenant. Vacate first.');
        }

        // Load all tenants, ordering unassigned ones first
        $tenants = Tenant::withCount(['flatTenants as is_assigned' => function ($query) {
            $query->where('status', 'active');
        }])
        ->orderBy('is_assigned', 'asc')
        ->latest()
        ->get();

        return view('admin.tenants.enroll', compact('building', 'flat', 'tenants'));
    }

    // ─── 3. Search tenant by phone/name (AJAX or form) ────────────────

    public function search(Request $request, $buildingId, $flatId)
    {
        $flat     = $this->getFlat($buildingId, $flatId);
        $building = $flat->building;

        $query   = $request->input('query');
        $tenants = collect();

        if ($query) {
            $tenants = Tenant::where('name', 'like', "%$query%")
                ->orWhere('phone', 'like', "%$query%")
                ->orWhere('nid_number', 'like', "%$query%")
                ->latest()->get();
        }

        return view('admin.tenants.enroll', compact('building', 'flat', 'tenants', 'query'));
    }

    // ─── 4. Create brand-new tenant form ──────────────────────────────

    public function create($buildingId, $flatId)
    {
        $flat     = $this->getFlat($buildingId, $flatId);
        $building = $flat->building;
        return view('admin.tenants.create', compact('building', 'flat'));
    }

    // ─── 5. Store new tenant + assign to flat ─────────────────────────

    public function store(Request $request, $buildingId, $flatId)
    {
        $flat = $this->getFlat($buildingId, $flatId);

        try {
            DB::beginTransaction();

            // Build arrays for JSON fields
            $membersInfo = [];
            if ($request->has('member_name') && is_array($request->member_name)) {
                foreach ($request->member_name as $i => $mname) {
                    if ($mname) {
                        $membersInfo[] = [
                            'name'     => $mname,
                            'age'      => $request->member_age[$i] ?? null,
                            'relation' => $request->member_relation[$i] ?? null,
                            'phone'    => $request->member_phone[$i] ?? null,
                        ];
                    }
                }
            }

            $children = [];
            if ($request->no_of_children > 0 && $request->has('child_name') && is_array($request->child_name)) {
                for ($i = 0; $i < $request->no_of_children; $i++) {
                    if (isset($request->child_name[$i]) && $request->child_name[$i] != '') {
                        $bcDocs = [];
                        if ($request->hasFile("child_birthcertificate.{$i}")) {
                            foreach ($request->file("child_birthcertificate.{$i}") as $file) {
                                $bcDocs[] = uploadFileDirect($file, 'admin/assets/documents/tenants/children');
                            }
                        }
                        $children[] = [
                            'name'             => $request->child_name[$i],
                            'gender'           => $request->child_gender[$i] ?? '',
                            'dob'              => $request->child_dob[$i] ?? '',
                            'birthcertificate' => $bcDocs
                        ];
                    }
                }
            }

            $helpInfo = [];
            if ($request->no_of_help > 0 && $request->has('help_name') && is_array($request->help_name)) {
                for ($i = 0; $i < $request->no_of_help; $i++) {
                    if (isset($request->help_name[$i]) && $request->help_name[$i] != '') {
                        $helpInfo[] = [
                            'name'   => $request->help_name[$i],
                            'nid'    => $request->help_nid[$i] ?? '',
                            'mobile' => $request->help_mobile[$i] ?? '',
                            'address'=> $request->help_address[$i] ?? ''
                        ];
                    }
                }
            }

            $driverInfo = [];
            if ($request->no_of_driver > 0 && $request->has('drv_name') && is_array($request->drv_name)) {
                for ($i = 0; $i < $request->no_of_driver; $i++) {
                    if (isset($request->drv_name[$i]) && $request->drv_name[$i] != '') {
                        $driverInfo[] = [
                            'name'   => $request->drv_name[$i],
                            'nid'    => $request->drv_nid[$i] ?? '',
                            'mobile' => $request->drv_mobile[$i] ?? '',
                            'address'=> $request->drv_address[$i] ?? ''
                        ];
                    }
                }
            }

            $educationInfo = [];
            if ($request->has('edu_exam') && is_array($request->edu_exam)) {
                foreach ($request->edu_exam as $i => $exam) {
                    if ($exam) {
                        $eduDocs = [];
                        if ($request->hasFile("edu_document.{$i}")) {
                            foreach ($request->file("edu_document.{$i}") as $file) {
                                $eduDocs[] = uploadFileDirect($file, 'admin/assets/documents/tenants/education');
                            }
                        }
                        $educationInfo[] = [
                            'exam'        => $exam,
                            'institution' => $request->edu_institution[$i] ?? '',
                            'year'        => $request->edu_year[$i] ?? '',
                            'documents'   => $eduDocs
                        ];
                    }
                }
            }

            $occupationInfo = [];
            if ($request->has('occupation_type') && is_array($request->occupation_type)) {
                foreach ($request->occupation_type as $i => $type) {
                    if ($type === 'job') {
                        $occDocs = [];
                        if ($request->hasFile("occupation_document.{$i}")) {
                            foreach ($request->file("occupation_document.{$i}") as $file) {
                                $occDocs[] = uploadFileDirect($file, 'admin/assets/documents/tenants/occupation');
                            }
                        }
                        $occupationInfo[] = [
                            'type'      => 'job',
                            'company'   => $request->occupation_company[$i] ?? '',
                            'address'   => $request->occupation_address[$i] ?? '',
                            'documents' => $occDocs
                        ];
                    } elseif ($type === 'business') {
                        $tradeDocs = [];
                        if ($request->hasFile("trade_license_document.{$i}")) {
                            foreach ($request->file("trade_license_document.{$i}") as $file) {
                                $tradeDocs[] = uploadFileDirect($file, 'admin/assets/documents/tenants/business');
                            }
                        }
                        $tinDocs = [];
                        if ($request->hasFile("tin_certificate_document.{$i}")) {
                            foreach ($request->file("tin_certificate_document.{$i}") as $file) {
                                $tinDocs[] = uploadFileDirect($file, 'admin/assets/documents/tenants/business');
                            }
                        }
                        $otherDocs = [];
                        if ($request->hasFile("business_other_document.{$i}")) {
                            foreach ($request->file("business_other_document.{$i}") as $file) {
                                $otherDocs[] = uploadFileDirect($file, 'admin/assets/documents/tenants/business');
                            }
                        }
                        $occupationInfo[] = [
                            'type'             => 'business',
                            'business_name'    => $request->business_name[$i] ?? '',
                            'business_address' => $request->business_address[$i] ?? '',
                            'trade_docs'       => $tradeDocs,
                            'tin_docs'         => $tinDocs,
                            'other_docs'       => $otherDocs,
                        ];
                    }
                }
            }

            // Helper to handle multiple file uploads
            $uploadMulti = function($key, $path) use ($request) {
                $paths = [];
                if ($request->hasFile($key)) {
                    foreach ($request->file($key) as $file) {
                        $paths[] = uploadFileDirect($file, $path);
                    }
                }
                return empty($paths) ? null : $paths;
            };

            $tenantData = [
                'tenant_id'                  => generateTenantId(),
                'image'                      => $request->hasFile('image') ? uploadFileDirect($request->file('image'), 'admin/assets/images/tenants') : null,
                'name'                       => $request->name,
                'father_name'                => $request->father_name,
                'mother_name'                => $request->mother_name,
                'gender'                     => $request->gender,
                'dob'                        => $request->dob,
                'permanent_address'          => $request->permanent_address,
                'phone'                      => $request->phone,
                'email'                      => $request->email,
                'blood_group'                => $request->blood_group,
                'religion'                   => $request->religion,
                'nationality'                => $request->nationality,
                'emergency_contact_name'     => $request->emergency_contact_name,
                'emergency_contact_relation' => $request->emergency_contact_relation,
                'emergency_contact_phone'    => $request->emergency_contact_phone,
                'emergency_contact_address'  => $request->emergency_contact_address,
                'marital_status'             => $request->marital_status,
                'spouse_name'                => $request->marital_status === 'married' ? $request->spouse_name : null,
                'spouse_contact_number'      => $request->marital_status === 'married' ? $request->spouse_contact_number : null,
                'spouse_father_name'         => $request->marital_status === 'married' ? $request->spouse_father_name : null,
                'spouse_mother_name'         => $request->marital_status === 'married' ? $request->spouse_mother_name : null,
                'spouse_blood_group'         => $request->marital_status === 'married' ? $request->spouse_blood_group : null,
                'spouse_date_of_birth'       => $request->marital_status === 'married' ? $request->spouse_date_of_birth : null,
                'no_of_children'             => $request->marital_status === 'married' ? ($request->no_of_children ?? 0) : 0,
                'children_info'              => $request->marital_status === 'married' && count($children) ? $children : null,
                'occupation_info'            => count($occupationInfo) ? $occupationInfo : null,
                'education_info'             => count($educationInfo) ? $educationInfo : null,
                'nid_number'                 => $request->nid_number,
                'nid_document'               => $uploadMulti('nid_document', 'admin/assets/documents/tenants/nid'),
                'driving_licence_number'     => $request->driving_licence_number,
                'driving_licence_expiry'     => $request->driving_licence_expiry,
                'driving_licence_document'   => $uploadMulti('driving_licence_document', 'admin/assets/documents/tenants/driving_licence'),
                'passport_number'            => $request->passport_number,
                'passport_expiry'            => $request->passport_expiry,
                'passport_document'          => $uploadMulti('passport_document', 'admin/assets/documents/tenants/passport'),
                'members_info'               => count($membersInfo) ? $membersInfo : null,
                'no_of_help'                 => $request->no_of_help ?? 0,
                'help_info'                  => count($helpInfo) ? $helpInfo : null,
                'no_of_driver'               => $request->no_of_driver ?? 0,
                'driver_info'                => count($driverInfo) ? $driverInfo : null,
                'prev_owner_name'            => $request->prev_owner_name,
                'prev_owner_phone'           => $request->prev_owner_phone,
                'prev_flat_address'          => $request->prev_flat_address,
                'prev_leaving_reason'        => $request->prev_leaving_reason,
            ];

            $tenant = Tenant::create($tenantData);

            DB::commit();
            return redirect()->route('admin.tenants.enroll', [$flat->building_id, $flat->id])
                ->with('success', 'Tenant profile created successfully. You can now assign them below.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage() . ' line: ' . $e->getLine());
        }
    }

    // ─── 6. Assign existing tenant to flat ────────────────────────────

    public function assign(Request $request, $buildingId, $flatId)
    {
        $flat = $this->getFlat($buildingId, $flatId);

        // Filter out empty file inputs from the request before validation
        foreach (['advance_document', 'agreement_document', 'police_form_document', 'notice_document', 'house_rent_copy'] as $key) {
            if ($request->has($key) || $request->hasFile($key)) {
                $files = $request->file($key);
                if (is_array($files)) {
                    $validFiles = [];
                    foreach ($files as $file) {
                        if ($file && $file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                            $validFiles[] = $file;
                        }
                    }
                    if (empty($validFiles)) {
                        $request->files->remove($key);
                        $request->request->remove($key);
                    } else {
                        $request->files->set($key, $validFiles);
                    }
                }
            }
        }

        $request->validate([
            'tenant_id'              => 'required|exists:tenants,id',
            'start_date'             => 'required|date',
            'advance_amount'         => 'nullable|numeric|min:0',
            'advance_document'       => 'nullable|array',
            'advance_document.*'     => 'file|mimes:pdf,jpg,png',
            'agreement_document'     => 'nullable|array',
            'agreement_document.*'   => 'file|mimes:pdf,jpg,png',
            'police_form_document'   => 'nullable|array',
            'police_form_document.*' => 'file|mimes:pdf,jpg,png',
            'notice_document'        => 'nullable|array',
            'notice_document.*'      => 'file|mimes:pdf,jpg,png',
            'house_rent_copy'        => 'nullable|array',
            'house_rent_copy.*'      => 'file|mimes:pdf,jpg,png',
        ]);

        try {
            DB::beginTransaction();
            $this->assignTenantToFlat($request, $flat, $request->tenant_id);
            DB::commit();
            return redirect()->route('admin.tenants.index', [$buildingId, $flatId])
                ->with('success', 'Tenant assigned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ─── 7. Show tenant profile ───────────────────────────────────────

    public function show($buildingId, $flatId, $tenantId)
    {
        $flat      = $this->getFlat($buildingId, $flatId);
        $building  = $flat->building;
        $tenant    = Tenant::findOrFail($tenantId);
        $flatTenant = FlatTenant::where('flat_id', $flat->id)
            ->where('tenant_id', $tenantId)
            ->latest()->first();
        
        $bills = \App\Models\MonthlyBill::where('flat_tenant_id', $flatTenant->id)
            ->orderBy('bill_year', 'desc')
            ->orderBy('bill_month_number', 'desc')
            ->get();
            
        return view('admin.tenants.show', compact('building', 'flat', 'tenant', 'flatTenant', 'bills'));
    }
    // ─── 8. Edit tenant ───────────────────────────────────────────────

    public function edit($buildingId, $flatId, $tenantId)
    {
        $flat = $this->getFlat($buildingId, $flatId);
        $building = $flat->building;
        $tenant = Tenant::findOrFail($tenantId);
        $flatTenant = FlatTenant::where('flat_id', $flat->id)
            ->where('tenant_id', $tenant->id)
            ->first();

        return view('admin.tenants.edit', compact('building', 'flat', 'tenant', 'flatTenant'));
    }

    // ─── 9. Update tenant ─────────────────────────────────────────────

    public function update(Request $request, $buildingId, $flatId, $tenantId)
    {
        $flat       = $this->getFlat($buildingId, $flatId);
        $tenant     = Tenant::findOrFail($tenantId);
        $flatTenant = FlatTenant::where('flat_id', $flat->id)
            ->where('tenant_id', $tenantId)
            ->latest()
            ->firstOrFail();

        try {
            DB::beginTransaction();

            // Build arrays for JSON fields
            $membersInfo = [];
            if ($request->has('member_name') && is_array($request->member_name)) {
                foreach ($request->member_name as $i => $mname) {
                    if ($mname) {
                        $membersInfo[] = [
                            'name'     => $mname,
                            'age'      => $request->member_age[$i] ?? null,
                            'relation' => $request->member_relation[$i] ?? null,
                            'phone'    => $request->member_phone[$i] ?? null,
                        ];
                    }
                }
            }

            $children = [];
            if ($request->no_of_children > 0 && $request->has('child_name') && is_array($request->child_name)) {
                for ($i = 0; $i < $request->no_of_children; $i++) {
                    if (isset($request->child_name[$i]) && $request->child_name[$i] != '') {
                        $bcDocs = [];
                        if (isset($tenant->children_info[$i]['birthcertificate']) && is_array($tenant->children_info[$i]['birthcertificate'])) {
                            $bcDocs = $tenant->children_info[$i]['birthcertificate'];
                        }
                        if ($request->hasFile("child_birthcertificate.{$i}")) {
                            foreach ($request->file("child_birthcertificate.{$i}") as $file) {
                                $bcDocs[] = uploadFileDirect($file, 'admin/assets/documents/tenants/children');
                            }
                        }
                        $children[] = [
                            'name'             => $request->child_name[$i],
                            'gender'           => $request->child_gender[$i] ?? '',
                            'dob'              => $request->child_dob[$i] ?? '',
                            'birthcertificate' => $bcDocs
                        ];
                    }
                }
            }

            $helpInfo = [];
            if ($request->no_of_help > 0 && $request->has('help_name') && is_array($request->help_name)) {
                for ($i = 0; $i < $request->no_of_help; $i++) {
                    if (isset($request->help_name[$i]) && $request->help_name[$i] != '') {
                        $helpInfo[] = [
                            'name'   => $request->help_name[$i],
                            'nid'    => $request->help_nid[$i] ?? '',
                            'mobile' => $request->help_mobile[$i] ?? '',
                            'address'=> $request->help_address[$i] ?? ''
                        ];
                    }
                }
            }

            $driverInfo = [];
            if ($request->no_of_driver > 0 && $request->has('drv_name') && is_array($request->drv_name)) {
                for ($i = 0; $i < $request->no_of_driver; $i++) {
                    if (isset($request->drv_name[$i]) && $request->drv_name[$i] != '') {
                        $driverInfo[] = [
                            'name'   => $request->drv_name[$i],
                            'nid'    => $request->drv_nid[$i] ?? '',
                            'mobile' => $request->drv_mobile[$i] ?? '',
                            'address'=> $request->drv_address[$i] ?? ''
                        ];
                    }
                }
            }

            $educationInfo = [];
            if ($request->has('edu_exam') && is_array($request->edu_exam)) {
                foreach ($request->edu_exam as $i => $exam) {
                    if ($exam) {
                        $eduDocs = [];
                        if (isset($tenant->education_info[$i]['documents']) && is_array($tenant->education_info[$i]['documents'])) {
                            $eduDocs = $tenant->education_info[$i]['documents'];
                        }
                        if ($request->hasFile("edu_document.{$i}")) {
                            foreach ($request->file("edu_document.{$i}") as $file) {
                                $eduDocs[] = uploadFileDirect($file, 'admin/assets/documents/tenants/education');
                            }
                        }
                        $educationInfo[] = [
                            'exam'        => $exam,
                            'institution' => $request->edu_institution[$i] ?? '',
                            'year'        => $request->edu_year[$i] ?? '',
                            'documents'   => $eduDocs
                        ];
                    }
                }
            }

            $occupationInfo = [];
            if ($request->has('occupation_type') && is_array($request->occupation_type)) {
                foreach ($request->occupation_type as $i => $type) {
                    if ($type === 'job') {
                        $occDocs = [];
                        if (isset($tenant->occupation_info[$i]['documents']) && is_array($tenant->occupation_info[$i]['documents'])) {
                            $occDocs = $tenant->occupation_info[$i]['documents'];
                        }
                        if ($request->hasFile("occupation_document.{$i}")) {
                            foreach ($request->file("occupation_document.{$i}") as $file) {
                                $occDocs[] = uploadFileDirect($file, 'admin/assets/documents/tenants/occupation');
                            }
                        }
                        $occupationInfo[] = [
                            'type'      => 'job',
                            'company'   => $request->occupation_company[$i] ?? '',
                            'address'   => $request->occupation_address[$i] ?? '',
                            'documents' => $occDocs
                        ];
                    } elseif ($type === 'business') {
                        $tradeDocs = [];
                        if (isset($tenant->occupation_info[$i]['trade_docs']) && is_array($tenant->occupation_info[$i]['trade_docs'])) {
                            $tradeDocs = $tenant->occupation_info[$i]['trade_docs'];
                        }
                        if ($request->hasFile("trade_license_document.{$i}")) {
                            foreach ($request->file("trade_license_document.{$i}") as $file) {
                                $tradeDocs[] = uploadFileDirect($file, 'admin/assets/documents/tenants/business');
                            }
                        }
                        $tinDocs = [];
                        if (isset($tenant->occupation_info[$i]['tin_docs']) && is_array($tenant->occupation_info[$i]['tin_docs'])) {
                            $tinDocs = $tenant->occupation_info[$i]['tin_docs'];
                        }
                        if ($request->hasFile("tin_certificate_document.{$i}")) {
                            foreach ($request->file("tin_certificate_document.{$i}") as $file) {
                                $tinDocs[] = uploadFileDirect($file, 'admin/assets/documents/tenants/business');
                            }
                        }
                        $otherDocs = [];
                        if (isset($tenant->occupation_info[$i]['other_docs']) && is_array($tenant->occupation_info[$i]['other_docs'])) {
                            $otherDocs = $tenant->occupation_info[$i]['other_docs'];
                        }
                        if ($request->hasFile("business_other_document.{$i}")) {
                            foreach ($request->file("business_other_document.{$i}") as $file) {
                                $otherDocs[] = uploadFileDirect($file, 'admin/assets/documents/tenants/business');
                            }
                        }
                        $occupationInfo[] = [
                            'type'             => 'business',
                            'business_name'    => $request->business_name[$i] ?? '',
                            'business_address' => $request->business_address[$i] ?? '',
                            'trade_docs'       => $tradeDocs,
                            'tin_docs'         => $tinDocs,
                            'other_docs'       => $otherDocs,
                        ];
                    }
                }
            }

            $uploadMulti = function($key, $path, $existing) use ($request) {
                $paths = $existing ?? [];
                if ($request->hasFile($key)) {
                    foreach ($request->file($key) as $file) {
                        $paths[] = uploadFileDirect($file, $path);
                    }
                }
                return empty($paths) ? null : $paths;
            };

            $tenantData = [
                'name'                       => $request->name ?? $tenant->name,
                'father_name'                => $request->father_name ?? $tenant->father_name,
                'mother_name'                => $request->mother_name ?? $tenant->mother_name,
                'gender'                     => $request->gender ?? $tenant->gender,
                'dob'                        => $request->dob ?? $tenant->dob,
                'permanent_address'          => $request->permanent_address ?? $tenant->permanent_address,
                'phone'                      => $request->phone ?? $tenant->phone,
                'email'                      => $request->email ?? $tenant->email,
                'blood_group'                => $request->blood_group ?? $tenant->blood_group,
                'religion'                   => $request->religion ?? $tenant->religion,
                'nationality'                => $request->nationality ?? $tenant->nationality,
                'emergency_contact_name'     => $request->emergency_contact_name ?? $tenant->emergency_contact_name,
                'emergency_contact_relation' => $request->emergency_contact_relation ?? $tenant->emergency_contact_relation,
                'emergency_contact_phone'    => $request->emergency_contact_phone ?? $tenant->emergency_contact_phone,
                'emergency_contact_address'  => $request->emergency_contact_address ?? $tenant->emergency_contact_address,
                'marital_status'             => $request->marital_status ?? $tenant->marital_status,
                'spouse_name'                => $request->marital_status === 'married' ? $request->spouse_name : null,
                'spouse_contact_number'      => $request->marital_status === 'married' ? $request->spouse_contact_number : null,
                'spouse_father_name'         => $request->marital_status === 'married' ? $request->spouse_father_name : null,
                'spouse_mother_name'         => $request->marital_status === 'married' ? $request->spouse_mother_name : null,
                'spouse_blood_group'         => $request->marital_status === 'married' ? $request->spouse_blood_group : null,
                'spouse_date_of_birth'       => $request->marital_status === 'married' ? $request->spouse_date_of_birth : null,
                'no_of_children'             => $request->marital_status === 'married' ? ($request->no_of_children ?? 0) : 0,
                'children_info'              => $request->marital_status === 'married' && count($children) ? $children : $tenant->children_info,
                'occupation_info'            => count($occupationInfo) ? $occupationInfo : $tenant->occupation_info,
                'education_info'             => count($educationInfo) ? $educationInfo : $tenant->education_info,
                'nid_number'                 => $request->nid_number ?? $tenant->nid_number,
                'driving_licence_number'     => $request->driving_licence_number ?? $tenant->driving_licence_number,
                'driving_licence_expiry'     => $request->driving_licence_expiry ?? $tenant->driving_licence_expiry,
                'passport_number'            => $request->passport_number ?? $tenant->passport_number,
                'passport_expiry'            => $request->passport_expiry ?? $tenant->passport_expiry,
                'members_info'               => count($membersInfo) ? $membersInfo : $tenant->members_info,
                'no_of_help'                 => $request->no_of_help ?? $tenant->no_of_help,
                'help_info'                  => count($helpInfo) ? $helpInfo : $tenant->help_info,
                'no_of_driver'               => $request->no_of_driver ?? $tenant->no_of_driver,
                'driver_info'                => count($driverInfo) ? $driverInfo : $tenant->driver_info,
                'prev_owner_name'            => $request->prev_owner_name ?? $tenant->prev_owner_name,
                'prev_owner_phone'           => $request->prev_owner_phone ?? $tenant->prev_owner_phone,
                'prev_flat_address'          => $request->prev_flat_address ?? $tenant->prev_flat_address,
                'prev_leaving_reason'        => $request->prev_leaving_reason ?? $tenant->prev_leaving_reason,
            ];

            if ($request->hasFile('image')) {
                $tenantData['image'] = uploadFileDirect($request->file('image'), 'admin/assets/images/tenants');
            }
            $tenantData['nid_document'] = $uploadMulti('nid_document', 'admin/assets/documents/tenants/nid', $tenant->nid_document);
            $tenantData['driving_licence_document'] = $uploadMulti('driving_licence_document', 'admin/assets/documents/tenants/driving_licence', $tenant->driving_licence_document);
            $tenantData['passport_document'] = $uploadMulti('passport_document', 'admin/assets/documents/tenants/passport', $tenant->passport_document);

            $tenant->update($tenantData);

            // Update flat_tenant docs
            $ftData = ['advance_amount' => $request->advance_amount ?? $flatTenant->advance_amount];
            foreach (['advance_document','agreement_document','police_form_document','notice_document','house_rent_copy'] as $doc) {
                if ($request->hasFile($doc)) {
                    $paths = $flatTenant->{$doc} ?? [];
                    foreach ($request->file($doc) as $file) {
                        $paths[] = uploadFileDirect($file, 'admin/assets/documents/tenants/assignment');
                    }
                    $ftData[$doc] = $paths;
                }
            }
            $flatTenant->update($ftData);

            DB::commit();
            return redirect()->route('admin.tenants.index', [$buildingId, $flatId])
                ->with('success', 'Tenant updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ─── 10. Vacate — end tenancy ─────────────────────────────────────

    public function vacate(Request $request, $buildingId, $flatId, $tenantId)
    {
        $flat       = $this->getFlat($buildingId, $flatId);
        $flatTenant = FlatTenant::where('flat_id', $flat->id)
            ->where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->firstOrFail();

        try {
            DB::beginTransaction();

            $flatTenant->update([
                'status'   => 'inactive',
                'end_date' => $request->end_date ?? now()->toDateString(),
                'notes'    => $request->vacate_notes ?? $flatTenant->notes,
            ]);

            $flat->update(['status' => 'vacant']);

            DB::commit();
            return redirect()->route('admin.tenants.index', [$buildingId, $flatId])
                ->with('success', 'Tenant vacated. Flat is now vacant.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ─── 11. Download tenant profile PDF ──────────────────────────────

    public function download($buildingId, $flatId, $tenantId)
    {
        $flat       = $this->getFlat($buildingId, $flatId);
        $building   = $flat->building;
        $tenant     = Tenant::findOrFail($tenantId);
        $flatTenant = FlatTenant::where('flat_id', $flat->id)
            ->where('tenant_id', $tenantId)
            ->latest()
            ->firstOrFail();

        $pdf = Pdf::loadView('admin.tenants.pdf', compact('building', 'flat', 'tenant', 'flatTenant'));
        return $pdf->download("tenant_{$tenant->tenant_id}_profile.pdf");
    }

    // ─── Private helper ───────────────────────────────────────────────

    private function assignTenantToFlat(Request $request, Flat $flat, int $tenantId): void
    {
        // Deactivate any old active assignment (safety)
        FlatTenant::where('flat_id', $flat->id)->where('status', 'active')
            ->update(['status' => 'inactive', 'end_date' => now()]);

        $docPath = 'admin/assets/documents/tenants/assignment';
        
        $uploadMulti = function($key) use ($request, $docPath) {
            $paths = [];
            if ($request->hasFile($key)) {
                foreach ($request->file($key) as $file) {
                    $paths[] = uploadFileDirect($file, $docPath);
                }
            }
            return empty($paths) ? null : $paths;
        };

        FlatTenant::create([
            'flat_id'              => $flat->id,
            'tenant_id'            => $tenantId,
            'start_date'           => $request->start_date,
            'advance_amount'       => $request->advance_amount ?? 0,
            'advance_document'     => $uploadMulti('advance_document'),
            'agreement_document'   => $uploadMulti('agreement_document'),
            'police_form_document' => $uploadMulti('police_form_document'),
            'notice_document'      => $uploadMulti('notice_document'),
            'house_rent_copy'      => $uploadMulti('house_rent_copy'),
            'status'               => 'active',
        ]);

        $flat->update(['status' => 'occupied']);
    }

}