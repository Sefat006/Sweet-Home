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

        $request->validate([
            'name'                       => 'required|string|max:255',
            'email'                      => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'                      => 'required|string|max:20',
            'date_of_birth'              => 'nullable|date',
            'blood_group'                => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'marital_status'             => 'nullable|in:single,married,divorced,widowed',
            'present_address'            => 'nullable|string|max:1000',
            'permanent_address'          => 'nullable|string|max:1000',
            'nid_number'                 => 'nullable|string|max:30',
            'passport_number'            => 'nullable|string|max:30',
            'passport_expiry'            => 'nullable|date',
            'tin_number'                 => 'nullable|string|max:30',
            'driving_licence_number'     => 'nullable|string|max:30',
            'driving_licence_expiry'     => 'nullable|date',
            'occupation_position'        => 'nullable|string|max:255',
            'occupation_company'         => 'nullable|string|max:255',
            'occupation_address'         => 'nullable|string|max:1000',
            'no_of_cars'                 => 'nullable|integer|min:0',
            'no_of_children'             => 'nullable|integer|min:0',
            'no_of_spouse'               => 'nullable|integer|min:0',
            'image'                      => 'nullable|image|max:2048',
            'nid_document'               => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
            'passport_document'          => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
            'tin_document'               => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
            'driving_licence_document'   => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
            'occupation_document'        => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
            'car_details_document'       => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
            'education_document'         => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
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
            'edu_date',
            'edu_year',
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
        ]);

        // ── File uploads ─────────────────────────────────────────────────
        $fileFields = [
            'image'                    => 'admin/assets/images/profiles',
            'nid_document'             => 'admin/assets/documents/nid',
            'passport_document'        => 'admin/assets/documents/passport',
            'tin_document'             => 'admin/assets/documents/tin',
            'driving_licence_document' => 'admin/assets/documents/driving_licence',
            'occupation_document'      => 'admin/assets/documents/occupation',
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

        // ── Education ────────────────────────────────────────────────────
        $exams      = $request->input('edu_exam', []);
        $institutes = $request->input('edu_institute', []);
        $dates      = $request->input('edu_date', []);
        $years      = $request->input('edu_year', []);
        $education  = [];
        foreach ($exams as $i => $exam) {
            if (!empty($exam) || !empty($institutes[$i])) {
                $education[] = [
                    'exam'      => $exam,
                    'institute' => $institutes[$i] ?? null,
                    'date'      => $dates[$i] ?? null,
                    'year'      => $years[$i] ?? null,
                ];
            }
        }
        $data['education'] = $education ?: null;

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
