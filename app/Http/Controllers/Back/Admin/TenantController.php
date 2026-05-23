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

        return view('admin.tenants.enroll', compact('building', 'flat'));
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

        $request->validate([
            // Personal
            'name'                       => 'required|string|max:200',
            'phone'                      => 'required|string|max:20',
            'email'                      => 'nullable|email|max:200',
            'image'                      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nid_number'                 => 'nullable|string|max:50',
            'nid_document'               => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'birth_cert_number'          => 'nullable|string|max:50',
            'birth_cert_document'        => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'dob'                        => 'nullable|date',
            'blood_group'                => 'nullable|string|max:10',
            'religion'                   => 'nullable|string|max:100',
            'nationality'                => 'nullable|string|max:100',
            'gender'                     => 'nullable|in:male,female,other',
            'marital_status'             => 'nullable|in:single,married,divorced,widowed',
            'spouse_name'                => 'nullable|string|max:200',
            'spouse_contact_number'      => 'nullable|string|max:20',
            'spouse_father_name'         => 'nullable|string|max:200',
            'spouse_mother_name'         => 'nullable|string|max:200',
            'spouse_blood_group'         => 'nullable|string|max:10',
            'spouse_date_of_birth'       => 'nullable|date',
            'passport_number'            => 'nullable|string|max:50',
            'passport_expiry'            => 'nullable|date',
            'passport_document'          => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'driving_licence_number'     => 'nullable|string|max:50',
            'driving_licence_expiry'     => 'nullable|date',
            'driving_licence_document'   => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'occupation'                 => 'nullable|string|max:200',
            'occupation_company'         => 'nullable|string|max:200',
            'occupation_address'         => 'nullable|string',
            'occupation_document'        => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'present_address'            => 'nullable|string',
            'permanent_address'          => 'nullable|string',
            'emergency_contact_name'     => 'nullable|string|max:200',
            'emergency_contact_phone'    => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:100',
            'previous_rent_info'         => 'nullable|string',
            'reason_to_change'           => 'nullable|string',
            'family_members_count'       => 'nullable|integer|min:1',
            'vehicle_info'               => 'nullable|string|max:255',
            'notes'                      => 'nullable|string',
            'no_of_children'             => 'nullable|integer|min:0',
            'child_name'                 => 'nullable|array',
            'child_gender'               => 'nullable|array',
            'child_dob'                  => 'nullable|array',
            // Family members
            'family_name'                => 'nullable|array',
            'family_name.*'              => 'nullable|string|max:200',
            'family_relation'            => 'nullable|array',
            'family_relation.*'          => 'nullable|string|max:100',
            'family_nid'                 => 'nullable|array',
            'family_nid.*'               => 'nullable|string|max:50',
            // Assignment docs
            'start_date'                 => 'required|date',
            'advance_amount'             => 'nullable|numeric|min:0',
            'advance_document'           => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'agreement_document'         => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'police_form_document'       => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'notice_document'            => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'house_rent_copy'            => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Build family_members JSON
            $familyMembers = [];
            if ($request->has('family_name') && is_array($request->family_name)) {
                foreach ($request->family_name as $i => $fname) {
                    if ($fname) {
                        $familyMembers[] = [
                            'name'     => $fname,
                            'relation' => $request->family_relation[$i] ?? null,
                            'nid'      => $request->family_nid[$i] ?? null,
                        ];
                    }
                }
            }

            // Build children info
            $children = [];
            if ($request->no_of_children > 0 && $request->has('child_name') && is_array($request->child_name)) {
                for ($i = 0; $i < $request->no_of_children; $i++) {
                    if (isset($request->child_name[$i]) && $request->child_name[$i] != '') {
                        $children[] = [
                            'name'   => $request->child_name[$i],
                            'gender' => $request->child_gender[$i] ?? '',
                            'dob'    => $request->child_dob[$i] ?? ''
                        ];
                    }
                }
            }

            // Upload tenant files
            $tenant = Tenant::create([
                'tenant_id'                  => generateTenantId(),
                'name'                       => $request->name,
                'phone'                      => $request->phone,
                'email'                      => $request->email,
                'image'                      => $request->hasFile('image')             ? $request->file('image')->store('admin/assets/images/tenants', 'public') : null,
                'nid_number'                 => $request->nid_number,
                'nid_document'               => $request->hasFile('nid_document')      ? $request->file('nid_document')->store('admin/assets/documents/tenants/nid', 'public') : null,
                'birth_cert_number'          => $request->birth_cert_number,
                'birth_cert_document'        => $request->hasFile('birth_cert_document') ? $request->file('birth_cert_document')->store('admin/assets/documents/tenants/birth-cert', 'public') : null,
                'dob'                        => $request->dob,
                'blood_group'                => $request->blood_group,
                'religion'                   => $request->religion,
                'nationality'                => $request->nationality,
                'gender'                     => $request->gender,
                'marital_status'             => $request->marital_status,
                'spouse_name'                => $request->marital_status === 'married' ? $request->spouse_name : null,
                'spouse_contact_number'      => $request->marital_status === 'married' ? $request->spouse_contact_number : null,
                'spouse_father_name'         => $request->marital_status === 'married' ? $request->spouse_father_name : null,
                'spouse_mother_name'         => $request->marital_status === 'married' ? $request->spouse_mother_name : null,
                'spouse_blood_group'         => $request->marital_status === 'married' ? $request->spouse_blood_group : null,
                'spouse_date_of_birth'       => $request->marital_status === 'married' ? $request->spouse_date_of_birth : null,
                'passport_number'            => $request->passport_number,
                'passport_expiry'            => $request->passport_expiry,
                'passport_document'          => $request->hasFile('passport_document') ? $request->file('passport_document')->store('admin/assets/documents/tenants/passport', 'public') : null,
                'driving_licence_number'     => $request->driving_licence_number,
                'driving_licence_expiry'     => $request->driving_licence_expiry,
                'driving_licence_document'   => $request->hasFile('driving_licence_document') ? $request->file('driving_licence_document')->store('admin/assets/documents/tenants/driving_licence', 'public') : null,
                'occupation'                 => $request->occupation,
                'occupation_company'         => $request->occupation_company,
                'occupation_address'         => $request->occupation_address,
                'occupation_document'        => $request->hasFile('occupation_document') ? $request->file('occupation_document')->store('admin/assets/documents/tenants/occupation', 'public') : null,
                'present_address'            => $request->present_address,
                'permanent_address'          => $request->permanent_address,
                'emergency_contact_name'     => $request->emergency_contact_name,
                'emergency_contact_phone'    => $request->emergency_contact_phone,
                'emergency_contact_relation' => $request->emergency_contact_relation,
                'previous_rent_info'         => $request->previous_rent_info,
                'reason_to_change'           => $request->reason_to_change,
                'family_members_count'       => $request->family_members_count ?? 1,
                'family_members'             => $familyMembers ?: null,
                'no_of_children'             => $request->marital_status === 'married' ? ($request->no_of_children ?? 0) : 0,
                'children_info'              => $request->marital_status === 'married' ? ($children ?: null) : null,
                'vehicle_info'               => $request->vehicle_info,
                'notes'                      => $request->notes,
            ]);

            // Assign tenant to flat
            $this->assignTenantToFlat($request, $flat, $tenant->id);

            DB::commit();
            return redirect()->route('admin.tenants.index', [$flat->building_id, $flat->id])
                ->with('success', 'Tenant enrolled successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ─── 6. Assign existing tenant to flat ────────────────────────────

    public function assign(Request $request, $buildingId, $flatId)
    {
        $flat = $this->getFlat($buildingId, $flatId);

        $request->validate([
            'tenant_id'            => 'required|exists:tenants,id',
            'start_date'           => 'required|date',
            'advance_amount'       => 'nullable|numeric|min:0',
            'advance_document'     => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'agreement_document'   => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'police_form_document' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'notice_document'      => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'house_rent_copy'      => 'nullable|file|mimes:pdf,jpg,png|max:2048',
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
        $flat      = $this->getFlat($buildingId, $flatId);
        $building  = $flat->building;
        $tenant    = Tenant::findOrFail($tenantId);
        $flatTenant = FlatTenant::where('flat_id', $flat->id)
            ->where('tenant_id', $tenantId)
            ->latest()
            ->firstOrFail();
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

        $request->validate([
            'name'                       => 'required|string|max:200',
            'phone'                      => 'required|string|max:20',
            'email'                      => 'nullable|email|max:200',
            'image'                      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nid_number'                 => 'nullable|string|max:50',
            'nid_document'               => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'birth_cert_number'          => 'nullable|string|max:50',
            'birth_cert_document'        => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'dob'                        => 'nullable|date',
            'blood_group'                => 'nullable|string|max:10',
            'religion'                   => 'nullable|string|max:100',
            'nationality'                => 'nullable|string|max:100',
            'gender'                     => 'nullable|in:male,female,other',
            'marital_status'             => 'nullable|in:single,married,divorced,widowed',
            'spouse_name'                => 'nullable|string|max:200',
            'spouse_contact_number'      => 'nullable|string|max:20',
            'spouse_father_name'         => 'nullable|string|max:200',
            'spouse_mother_name'         => 'nullable|string|max:200',
            'spouse_blood_group'         => 'nullable|string|max:10',
            'spouse_date_of_birth'       => 'nullable|date',
            'passport_number'            => 'nullable|string|max:50',
            'passport_expiry'            => 'nullable|date',
            'passport_document'          => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'driving_licence_number'     => 'nullable|string|max:50',
            'driving_licence_expiry'     => 'nullable|date',
            'driving_licence_document'   => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'occupation'                 => 'nullable|string|max:200',
            'occupation_company'         => 'nullable|string|max:200',
            'occupation_address'         => 'nullable|string',
            'occupation_document'        => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'present_address'            => 'nullable|string',
            'permanent_address'          => 'nullable|string',
            'emergency_contact_name'     => 'nullable|string|max:200',
            'emergency_contact_phone'    => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:100',
            'previous_rent_info'         => 'nullable|string',
            'reason_to_change'           => 'nullable|string',
            'family_members_count'       => 'nullable|integer|min:1',
            'vehicle_info'               => 'nullable|string|max:255',
            'notes'                      => 'nullable|string',
            'no_of_children'             => 'nullable|integer|min:0',
            'child_name'                 => 'nullable|array',
            'child_gender'               => 'nullable|array',
            'child_dob'                  => 'nullable|array',
            'family_name'                => 'nullable|array',
            'family_name.*'              => 'nullable|string|max:200',
            'family_relation'            => 'nullable|array',
            'family_nid'                 => 'nullable|array',
            // flat_tenant docs
            'advance_amount'             => 'nullable|numeric|min:0',
            'advance_document'           => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'agreement_document'         => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'police_form_document'       => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'notice_document'            => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'house_rent_copy'            => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $familyMembers = [];
            if ($request->has('family_name') && is_array($request->family_name)) {
                foreach ($request->family_name as $i => $fname) {
                    if ($fname) {
                        $familyMembers[] = [
                            'name'     => $fname,
                            'relation' => $request->family_relation[$i] ?? null,
                            'nid'      => $request->family_nid[$i] ?? null,
                        ];
                    }
                }
            }

            $children = [];
            if ($request->no_of_children > 0 && $request->has('child_name') && is_array($request->child_name)) {
                for ($i = 0; $i < $request->no_of_children; $i++) {
                    if (isset($request->child_name[$i]) && $request->child_name[$i] != '') {
                        $children[] = [
                            'name'   => $request->child_name[$i],
                            'gender' => $request->child_gender[$i] ?? '',
                            'dob'    => $request->child_dob[$i] ?? ''
                        ];
                    }
                }
            }

            $tenantData = $request->except(['_token','_method','family_name','family_relation','family_nid', 'child_name', 'child_gender', 'child_dob',
                'advance_amount','advance_document','agreement_document','police_form_document','notice_document','house_rent_copy','start_date']);

            foreach (['image','nid_document','birth_cert_document','occupation_document','passport_document','driving_licence_document'] as $file) {
                if ($request->hasFile($file)) {
                    $tenantData[$file] = $request->file($file)->store("admin/assets/documents/tenants/{$file}", 'public');
                } else {
                    unset($tenantData[$file]);
                }
            }

            if ($request->marital_status !== 'married') {
                $tenantData['spouse_name'] = null;
                $tenantData['spouse_contact_number'] = null;
                $tenantData['spouse_father_name'] = null;
                $tenantData['spouse_mother_name'] = null;
                $tenantData['spouse_blood_group'] = null;
                $tenantData['spouse_date_of_birth'] = null;
                $tenantData['no_of_children'] = 0;
                $tenantData['children_info'] = null;
            } else {
                $tenantData['children_info'] = $children ?: null;
            }

            $tenantData['family_members'] = $familyMembers ?: null;
            $tenant->update($tenantData);

            // Update flat_tenant docs
            $ftData = ['advance_amount' => $request->advance_amount ?? $flatTenant->advance_amount];
            foreach (['advance_document','agreement_document','police_form_document','notice_document','house_rent_copy'] as $doc) {
                if ($request->hasFile($doc)) {
                    $ftData[$doc] = $request->file($doc)->store('admin/assets/documents/tenants/assignment', 'public');
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

    // ─── Private helper ───────────────────────────────────────────────

    private function assignTenantToFlat(Request $request, Flat $flat, int $tenantId): void
    {
        // Deactivate any old active assignment (safety)
        FlatTenant::where('flat_id', $flat->id)->where('status', 'active')
            ->update(['status' => 'inactive', 'end_date' => now()]);

        $docPath = 'admin/assets/documents/tenants/assignment';

        FlatTenant::create([
            'flat_id'              => $flat->id,
            'tenant_id'            => $tenantId,
            'start_date'           => $request->start_date,
            'advance_amount'       => $request->advance_amount ?? 0,
            'advance_document'     => $request->hasFile('advance_document')     ? $request->file('advance_document')->store($docPath, 'public')     : null,
            'agreement_document'   => $request->hasFile('agreement_document')   ? $request->file('agreement_document')->store($docPath, 'public')   : null,
            'police_form_document' => $request->hasFile('police_form_document') ? $request->file('police_form_document')->store($docPath, 'public') : null,
            'notice_document'      => $request->hasFile('notice_document')      ? $request->file('notice_document')->store($docPath, 'public')      : null,
            'house_rent_copy'      => $request->hasFile('house_rent_copy')      ? $request->file('house_rent_copy')->store($docPath, 'public')      : null,
            'status'               => 'active',
        ]);

        $flat->update(['status' => 'occupied']);
    }
}