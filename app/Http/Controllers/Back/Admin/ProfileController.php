<?php

namespace App\Http\Controllers\Back\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form pre-filled with the authenticated user's data.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Filter out empty file inputs from array parameters before validation
        $arrayFileKeys = ['occupation_document', 'trade_license_document', 'tin_certificate_document', 'business_other_document', 'edu_document'];
        foreach ($arrayFileKeys as $key) {
            if ($request->has($key) || $request->hasFile($key)) {
                $fileGroups = $request->file($key);
                if (is_array($fileGroups)) {
                    $cleanedGroups = [];
                    foreach ($fileGroups as $idx => $files) {
                        if (is_array($files)) {
                            $validFiles = [];
                            foreach ($files as $file) {
                                if ($file && $file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                                    $validFiles[] = $file;
                                }
                            }
                            if (!empty($validFiles)) {
                                $cleanedGroups[$idx] = $validFiles;
                            }
                        }
                    }
                    if (empty($cleanedGroups)) {
                        $request->files->remove($key);
                        $request->request->remove($key);
                    } else {
                        $request->files->set($key, $cleanedGroups);
                    }
                }
            }
        }

        $request->validate([
            'name'                          => 'required|string|max:255',
            'email'                         => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'                         => 'required|string|max:20',
            'date_of_birth'                 => 'nullable|date',
            'blood_group'                   => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'marital_status'                => 'nullable|in:single,married,divorced,widowed',
            'present_address'               => 'nullable|string|max:1000',
            'permanent_address'             => 'nullable|string|max:1000',
            'nid_number'                    => 'nullable|string|max:30',
            'passport_number'               => 'nullable|string|max:30',
            'passport_expiry'               => 'nullable|date',
            'tin_number'                    => 'nullable|string|max:30',
            'driving_licence_number'        => 'nullable|string|max:30',
            'driving_licence_expiry'        => 'nullable|date',
            'no_of_cars'                    => 'nullable|integer|min:0',
            'no_of_children'                => 'nullable|integer|min:0',
            'no_of_spouse'                  => 'nullable|integer|min:0',
            'image'                         => 'nullable|image',
            'nid_document'                  => 'nullable|mimes:jpg,jpeg,png,pdf',
            'passport_document'             => 'nullable|mimes:jpg,jpeg,png,pdf',
            'tin_document'                  => 'nullable|mimes:jpg,jpeg,png,pdf',
            'driving_licence_document'      => 'nullable|mimes:jpg,jpeg,png,pdf',
            'car_details_document'          => 'nullable|mimes:jpg,jpeg,png,pdf',
            'education_document'            => 'nullable|mimes:jpg,jpeg,png,pdf',
            // Education validations
            'edu_exam'                      => 'nullable|array',
            'edu_exam.*'                    => 'nullable|string|max:255',
            'edu_institution'               => 'nullable|array',
            'edu_institution.*'             => 'nullable|string|max:255',
            'edu_year'                      => 'nullable|array',
            'edu_year.*'                    => 'nullable|integer|min:1900|max:2099',
            'edu_document'                  => 'nullable|array',
            'edu_document.*'                => 'nullable|array',
            'edu_document.*.*'              => 'file|mimes:pdf,jpg,jpeg,png',
            // Occupation validations
            'occupation_type'               => 'nullable|array',
            'occupation_type.*'             => 'nullable|in:job,business',
            'occupation_company'            => 'nullable|array',
            'occupation_company.*'          => 'nullable|string|max:255',
            'occupation_address'            => 'nullable|array',
            'occupation_address.*'          => 'nullable|string|max:1000',
            'occupation_document'           => 'nullable|array',
            'occupation_document.*'         => 'nullable|array',
            'occupation_document.*.*'       => 'file|mimes:pdf,jpg,jpeg,png',
            'business_name'                 => 'nullable|array',
            'business_name.*'               => 'nullable|string|max:255',
            'business_address'              => 'nullable|array',
            'business_address.*'            => 'nullable|string|max:1000',
            'trade_license_document'        => 'nullable|array',
            'trade_license_document.*'      => 'nullable|array',
            'trade_license_document.*.*'    => 'file|mimes:pdf,jpg,jpeg,png',
            'tin_certificate_document'      => 'nullable|array',
            'tin_certificate_document.*'    => 'nullable|array',
            'tin_certificate_document.*.*'  => 'file|mimes:pdf,jpg,jpeg,png',
            'business_other_document'       => 'nullable|array',
            'business_other_document.*'     => 'nullable|array',
            'business_other_document.*.*'   => 'file|mimes:pdf,jpg,jpeg,png',
        ]);

        $data = $request->except([
            '_token',
            '_method',
            'image',
            'nid_document',
            'passport_document',
            'tin_document',
            'driving_licence_document',
            'occupation_document',
            'car_details_document',
            'education_document',
            'edu_exam',
            'edu_institute',
            'edu_institution',
            'edu_date',
            'edu_year',
            'edu_document',
            'spouse_name',
            'spouse_dob',
            'spouse_status',
            'spouse_education',
            'child_name',
            'child_gender',
            'child_dob',
            'child_blood_group',
            'child_birth_certificate',
            'child_nid',
            'child_contact',
            'child_email',
            'child_present_address',
            'child_permanent_address',
            'child_education',
            // Occupation array inputs
            'occupation_type',
            'occupation_company',
            'occupation_address',
            'business_name',
            'business_address',
            'trade_license_document',
            'tin_certificate_document',
            'business_other_document'
        ]);

        // ── File uploads ─────────────────────────────────────────────────
        $fileFields = [
            'image'                    => 'admin/assets/images/profiles',
            'nid_document'             => 'admin/assets/documents/nid',
            'passport_document'        => 'admin/assets/documents/passport',
            'tin_document'             => 'admin/assets/documents/tin',
            'driving_licence_document' => 'admin/assets/documents/driving_licence',
            'car_details_document'     => 'admin/assets/documents/car_details',
            'education_document'       => 'admin/assets/documents/education',
        ];

        foreach ($fileFields as $field => $folder) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($user->$field && file_exists(public_path($user->$field))) {
                    @unlink(public_path($user->$field));
                }
                
                $file = $request->file($field);
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->move(public_path($folder), $filename);
                $data[$field] = $folder . '/' . $filename;
            }
        }

        // ── Occupation ───────────────────────────────────────────────────
        $occupationInfo = [];
        if ($request->has('occupation_type') && is_array($request->occupation_type)) {
            foreach ($request->occupation_type as $i => $type) {
                if ($type === 'job') {
                    $occDocs = [];
                    if (isset($user->occupation_info[$i]['documents']) && is_array($user->occupation_info[$i]['documents'])) {
                        $occDocs = $user->occupation_info[$i]['documents'];
                    }
                    if ($request->hasFile("occupation_document.{$i}")) {
                        foreach ($request->file("occupation_document.{$i}") as $file) {
                            $occDocs[] = uploadFileDirect($file, 'admin/assets/documents/profile/occupation');
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
                    if (isset($user->occupation_info[$i]['trade_docs']) && is_array($user->occupation_info[$i]['trade_docs'])) {
                        $tradeDocs = $user->occupation_info[$i]['trade_docs'];
                    }
                    if ($request->hasFile("trade_license_document.{$i}")) {
                        foreach ($request->file("trade_license_document.{$i}") as $file) {
                            $tradeDocs[] = uploadFileDirect($file, 'admin/assets/documents/profile/business');
                        }
                    }
                    $tinDocs = [];
                    if (isset($user->occupation_info[$i]['tin_docs']) && is_array($user->occupation_info[$i]['tin_docs'])) {
                        $tinDocs = $user->occupation_info[$i]['tin_docs'];
                    }
                    if ($request->hasFile("tin_certificate_document.{$i}")) {
                        foreach ($request->file("tin_certificate_document.{$i}") as $file) {
                            $tinDocs[] = uploadFileDirect($file, 'admin/assets/documents/profile/business');
                        }
                    }
                    $otherDocs = [];
                    if (isset($user->occupation_info[$i]['other_docs']) && is_array($user->occupation_info[$i]['other_docs'])) {
                        $otherDocs = $user->occupation_info[$i]['other_docs'];
                    }
                    if ($request->hasFile("business_other_document.{$i}")) {
                        foreach ($request->file("business_other_document.{$i}") as $file) {
                            $otherDocs[] = uploadFileDirect($file, 'admin/assets/documents/profile/business');
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
        $data['occupation_info'] = empty($occupationInfo) ? null : $occupationInfo;

        // ── Education ────────────────────────────────────────────────────
        $exams       = $request->input('edu_exam', []);
        $institutes  = $request->input('edu_institution', []);
        $years       = $request->input('edu_year', []);
        $education   = [];
        if (is_array($exams)) {
            foreach ($exams as $i => $exam) {
                if ($exam || !empty($institutes[$i])) {
                    $eduDocs = [];
                    if (isset($user->education[$i]['documents']) && is_array($user->education[$i]['documents'])) {
                        $eduDocs = $user->education[$i]['documents'];
                    }
                    if ($request->hasFile("edu_document.{$i}")) {
                        foreach ($request->file("edu_document.{$i}") as $file) {
                            $eduDocs[] = uploadFileDirect($file, 'admin/assets/documents/profile/education');
                        }
                    }
                    $education[] = [
                        'exam'        => $exam,
                        'institute'   => $institutes[$i] ?? '',
                        'year'        => $years[$i] ?? '',
                        'documents'   => $eduDocs
                    ];
                }
            }
        }
        $data['education'] = empty($education) ? null : $education;

        // ── Emergency contact ────────────────────────────────────────────
        $data['emergency_contact'] = $request->input('emergency_contact');

        // ── Spouse info ──────────────────────────────────────────────────
        $spouses = [];
        foreach ($request->input('spouse_name', []) as $i => $name) {
            if (!empty($name)) {
                $spouses[] = [
                    'name'      => $name,
                    'dob'       => $request->input('spouse_dob')[$i] ?? null,
                    'status'    => $request->input('spouse_status')[$i] ?? null,
                    'education' => $request->input('spouse_education')[$i] ?? null,
                ];
            }
        }
        $data['spouse_info'] = $spouses ?: null;

        // ── Children info ────────────────────────────────────────────────
        $children = [];
        foreach ($request->input('child_name', []) as $i => $name) {
            $children[] = [
                'name'              => $name,
                'gender'            => $request->input('child_gender')[$i] ?? null,
                'dob'               => $request->input('child_dob')[$i] ?? null,
                'blood_group'       => $request->input('child_blood_group')[$i] ?? null,
                'birth_certificate' => $request->input('child_birth_certificate')[$i] ?? null,
                'nid'               => $request->input('child_nid')[$i] ?? null,
                'contact'           => $request->input('child_contact')[$i] ?? null,
                'email'             => $request->input('child_email')[$i] ?? null,
                'present_address'   => $request->input('child_present_address')[$i] ?? null,
                'permanent_address' => $request->input('child_permanent_address')[$i] ?? null,
                'education'         => $request->input('child_education')[$i] ?? null,
            ];
        }
        $data['children_info'] = $children ?: null;

        $data['profile_completed'] = 1;

        $user->update($data);

        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully!');
    }

    /**
     * Show the profile view page (read-only).
     */
    public function show()
    {
        $user = Auth::user();
        return view('admin.profile.show', compact('user'));
    }
}
