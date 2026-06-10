@extends('admin.layouts.app')

@push('styles')
<style>
    :root {
        --pf-accent: #2563eb;
        --pf-accent-lt: #eff6ff;
        --pf-danger: #dc2626;
        --pf-border: #e2e8f0;
        --pf-label: #374151;
        --pf-muted: #6b7280;
        --pf-bg: #f8fafc;
        --pf-card: #ffffff;
        --pf-radius: 10px;
        --pf-shadow: 0 1px 4px rgba(0, 0, 0, .07);
    }

    /* Required asterisk */
    .req { color: #ef4444; font-weight: 700; }

    /* Multi-file drop zone */
    .pf-file {
        border: 2px dashed var(--pf-border);
        border-radius: 8px;
        padding: 13px 16px;
        cursor: pointer;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        transition: border-color .15s, background .15s;
        background: rgba(255,255,255,0.05);
        min-height: 56px;
    }
    .pf-file:hover { border-color: var(--pf-accent); background: rgba(37,99,235,.1); }
    .pf-file.dragover { border-color: var(--pf-accent); background: rgba(37,99,235,.15); }
    .pf-file__icon {
        width: 36px; height: 36px; border-radius: 8px;
        background: rgba(37,99,235,.2);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: background .15s; margin-top: 2px;
    }
    .pf-file:hover .pf-file__icon { background: rgba(37,99,235,.3); }
    .pf-file__icon svg { width: 17px; height: 17px; stroke: var(--pf-accent); }
    .pf-file__text { flex: 1; min-width: 0; }
    .pf-file__cta { font-size: .8rem; font-weight: 600; color: var(--pf-accent); line-height: 1.4; }
    .pf-file__hint { font-size: .72rem; color: #999; line-height: 1.3; margin-top: 2px; }
    .pf-file__existing { font-size: .73rem; color: #16a34a; font-weight: 500; margin-top: 2px; }
    .pf-file__names { margin-top: 6px; display: flex; flex-wrap: wrap; gap: 5px; }
    .pf-file__tag {
        display: inline-flex; align-items: center; gap: 5px;
        background: rgba(37,99,235,.18); border-radius: 5px;
        padding: 2px 8px; font-size: .72rem; color: #93c5fd; max-width: 200px;
    }
    .pf-file__tag span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .pf-file__tag button {
        background: none; border: none; color: #f87171; font-size: .75rem;
        cursor: pointer; padding: 0; line-height: 1; flex-shrink: 0;
    }

    /* Repeatable rows */
    .pf-row {
        display: grid;
        gap: 8px;
        margin-bottom: 8px;
        align-items: center;
    }
    .pf-row-4 { grid-template-columns: 1fr 1fr 1fr 32px; }
    .pf-row-3 { grid-template-columns: 1fr 1fr 1fr 32px; }
    .pf-row-5 { grid-template-columns: 40px 1fr 80px 1fr 100px 32px; }
    .pf-row-help { grid-template-columns: 1fr 1fr 1fr 1fr 32px; }

    @media(max-width: 768px) {
        .pf-row-4, .pf-row-3, .pf-row-5, .pf-row-help { grid-template-columns: 1fr 1fr; }
        .pf-row .btn-pf-del { grid-column: span 2; justify-self: start; }
    }

    .btn-pf-del {
        width: 30px; height: 30px; border-radius: 6px;
        background: #fee2e2; border: none; color: var(--pf-danger);
        font-size: .85rem; cursor: pointer;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .btn-pf-add {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 14px; border-radius: 7px;
        background: rgba(37,99,235,.15); border: 1px solid rgba(37,99,235,.35);
        color: #60a5fa; font-size: .82rem; font-weight: 600; cursor: pointer;
        transition: background .15s;
    }
    .btn-pf-add:hover { background: rgba(37,99,235,.28); }

    /* Section heading */
    .sec-head {
        color: #000 !important;
        border-bottom: 1px solid #ccc;
        padding-bottom: .45rem;
        margin-bottom: 1rem;
        margin-top: 1.5rem;
        font-weight: 700;
    }
    .sub-head {
        color: #333 !important;
        border-bottom: 1px solid #ddd;
        padding-bottom: .35rem;
        margin-bottom: .85rem;
        margin-top: 1rem;
        font-size: 1rem;
        font-weight: 600;
    }

    /* Occupation tabs */
    .occ-card {
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 8px; padding: 16px; margin-bottom: 12px;
    }
    .occ-type-row { display: flex; gap: 10px; align-items: center; margin-bottom: 12px; }
    .occ-type-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 12px; border-radius: 20px; font-size: .78rem; font-weight: 600;
        border: 1.5px solid rgba(37,99,235,.5); color: #93c5fd;
        background: rgba(37,99,235,.12); cursor: pointer;
        transition: background .15s, border-color .15s;
    }
    .occ-type-badge.active { background: rgba(37,99,235,.35); border-color: #2563eb; color: #fff; }

    /* Children row */
    .pf-child-row { display: grid; grid-template-columns: 1fr 120px 140px 1fr 32px; gap: 8px; margin-bottom: 8px; align-items: center; }
    @media(max-width: 768px) { .pf-child-row { grid-template-columns: 1fr 1fr; } }

    /* Hide number arrows */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield; /* Firefox */
    }

    /* Tenant Image */
    .upload-container { transition: border-color .15s; }
    .upload-container:hover { border-color: #2563eb !important; }
</style>
@endpush

@section('content')
@php
    $building = $building ?? (object)['id' => 1, 'name' => 'Static Building'];
    $flat = $flat ?? (object)['id' => 1, 'flat_name' => 'Static Flat'];
    $tenant = $tenant ?? null;
    $flatTenant = $flatTenant ?? null;
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Create New Tenant</h2>
                        <p style="color:white !important;" class="mb-0">Building: {{ $building->name }} | Flat: {{ $flat->flat_name }}</p>
                    </div>
                </div>
                <div class="breadcrumb__content__right d-flex gap-2">
                    <a href="{{ route('admin.tenants.enroll', [$building->id, $flat->id]) }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back to Search
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card bg-style">
                <div class="card-body p-4">
                    <form action="{{ route('admin.tenants.store', [$building->id, $flat->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- ════════════════════════════════════════
                             SECTION 1 · PERSONAL INFORMATION
                        ════════════════════════════════════════ -->
                        <h4 class="sec-head">1. Personal Information</h4>
                        <div class="row">

                            {{-- Tenant Image --}}
                            <div class="col-md-12 mb-4">
                                <label class="form-label">Tenant Image</label>
                                <div class="upload-container text-center border rounded p-3"
                                     style="border: 2px dashed #ccc !important; cursor: pointer; background: rgba(255,255,255,0.05); max-width: 260px;"
                                     onclick="document.getElementById('tenant_image').click()">
                                    @if(isset($tenant) && $tenant->image)
                                        <img src="{{ asset($tenant->image) }}" id="image_preview" style="max-height: 110px; max-width: 100%; border-radius: 8px;">
                                        <div id="upload_icon" style="display:none;"><i class="fa-solid fa-cloud-arrow-up fa-2x text-secondary"></i><p class="mt-2 mb-0 text-white" style="font-size:.85rem;">Click or drag image here</p></div>
                                    @else
                                        <div id="upload_icon"><i class="fa-solid fa-cloud-arrow-up fa-2x text-secondary"></i><p class="mt-2 mb-0 text-white" style="font-size:.85rem;">Click or drag image here</p></div>
                                        <img src="" id="image_preview" style="max-height: 110px; max-width: 100%; border-radius: 8px; display: none;">
                                    @endif
                                </div>
                                <input type="file" name="image" id="tenant_image" class="d-none" accept="image/*" onchange="previewImage(this)">
                            </div>

                            {{-- Name --}}
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Full Name <span class="req">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $tenant->name ?? '') }}" required>
                            </div>

                            {{-- Father Name --}}
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Father Name</label>
                                <input type="text" name="father_name" class="form-control" value="{{ old('father_name', $tenant->father_name ?? '') }}">
                            </div>

                            {{-- Mother Name --}}
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Mother Name</label>
                                <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name', $tenant->mother_name ?? '') }}">
                            </div>

                            {{-- Gender --}}
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="">Select Gender</option>
                                    <option value="male"   {{ old('gender') == 'male'   ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other"  {{ old('gender') == 'other'  ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            {{-- Date of Birth --}}
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" value="{{ old('dob', $tenant->dob ?? '') }}">
                            </div>

                            {{-- Permanent Address --}}
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Permanent Address</label>
                                <textarea name="permanent_address" class="form-control" rows="2">{{ old('permanent_address') }}</textarea>
                            </div>

                            {{-- Phone --}}
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Phone <span class="req">*</span></label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $tenant->phone ?? '') }}" required>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $tenant->email ?? '') }}">
                            </div>

                            {{-- Blood Group --}}
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Blood Group</label>
                                <input type="text" name="blood_group" class="form-control" value="{{ old('blood_group', $tenant->blood_group ?? '') }}" placeholder="e.g. O+">
                            </div>

                            {{-- Religion --}}
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Religion</label>
                                <input type="text" name="religion" class="form-control" value="{{ old('religion', $tenant->religion ?? '') }}">
                            </div>

                            {{-- Nationality --}}
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Nationality</label>
                                <input type="text" name="nationality" class="form-control" value="{{ old('nationality', $tenant->nationality ?? '') }}">
                            </div>
                        </div>


                        <!-- ════════════════════════════════════════
                             MARITAL STATUS & SPOUSE / CHILDREN
                        ════════════════════════════════════════ -->
                        <div class="row">
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Marital Status</label>
                                <select name="marital_status" class="form-control" id="marital_status" onchange="toggleSpouse()">
                                    <option value="">Select Status</option>
                                    <option value="single"   {{ old('marital_status') == 'single'   ? 'selected' : '' }}>Single</option>
                                    <option value="married"  {{ old('marital_status') == 'married'  ? 'selected' : '' }}>Married</option>
                                    <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="widowed"  {{ old('marital_status') == 'widowed'  ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>
                        </div>

                        <div id="spouse_section" style="display:none;">
                            <h5 class="sub-head">Spouse Information</h5>
                            <div class="row">
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label class="form-label">Spouse Name</label>
                                    <input type="text" name="spouse_name" class="form-control" value="{{ old('spouse_name', $tenant->spouse_name ?? '') }}">
                                </div>
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label class="form-label">Contact Number</label>
                                    <input type="text" name="spouse_contact_number" class="form-control" value="{{ old('spouse_contact_number', $tenant->spouse_contact_number ?? '') }}">
                                </div>
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label class="form-label">Father Name</label>
                                    <input type="text" name="spouse_father_name" class="form-control" value="{{ old('spouse_father_name', $tenant->spouse_father_name ?? '') }}">
                                </div>
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label class="form-label">Mother Name</label>
                                    <input type="text" name="spouse_mother_name" class="form-control" value="{{ old('spouse_mother_name', $tenant->spouse_mother_name ?? '') }}">
                                </div>
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label class="form-label">Blood Group</label>
                                    <input type="text" name="spouse_blood_group" class="form-control" value="{{ old('spouse_blood_group', $tenant->spouse_blood_group ?? '') }}">
                                </div>
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="spouse_date_of_birth" class="form-control" value="{{ old('spouse_date_of_birth', $tenant->spouse_date_of_birth ?? '') }}">
                                </div>
                            </div>

                            <h5 class="sub-head">Children Information</h5>
                            <div class="row">
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <label class="form-label">Number of Children</label>
                                    <input type="number" name="no_of_children" id="no_of_children" class="form-control" min="0" value="{{ old('no_of_children', $tenant->no_of_children ?? 0) }}">
                                </div>
                            </div>
                            <div id="children_rows"></div>
                        </div>


                        <!-- ════════════════════════════════════════
                             EMERGENCY CONTACT
                        ════════════════════════════════════════ -->
                        <h5 class="sub-head">Emergency Contact</h5>
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}">
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <label class="form-label">Relation</label>
                                <input type="text" name="emergency_contact_relation" class="form-control" value="{{ old('emergency_contact_relation') }}">
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <label class="form-label">Contact Number</label>
                                <input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone') }}">
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" name="emergency_contact_address" class="form-control" value="{{ old('emergency_contact_address', $tenant->emergency_contact_address ?? '') }}">
                            </div>
                        </div>



                        <!-- ════════════════════════════════════════
                             SECTION 2 · OCCUPATION
                        ════════════════════════════════════════ -->
                        <h4 class="sec-head">2. Occupation</h4>
                        <div id="occupation_list"></div>
                        <button type="button" class="btn-pf-add mb-3" onclick="addOccupation()">
                            <i class="fa-solid fa-plus"></i> Add Occupation
                        </button>

                        <!-- ════════════════════════════════════════
                             SECTION 3 · EDUCATION
                        ════════════════════════════════════════ -->
                        <h4 class="sec-head">3. Education</h4>
                        <div id="education_list"></div>
                        <button type="button" class="btn-pf-add mb-3" onclick="addEducation()">
                            <i class="fa-solid fa-plus"></i> Add Education
                        </button>

                        <!-- ════════════════════════════════════════
                             SECTION 4 · IDENTITY DOCUMENTS
                        ════════════════════════════════════════ -->
                        <h4 class="sec-head">4. Identity Documents</h4>
                        <div class="row">
                            {{-- NID --}}
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">NID Number</label>
                                <input type="text" name="nid_number" class="form-control" value="{{ old('nid_number', $tenant->nid_number ?? '') }}">
                            </div>
                            <div class="col-md-8 col-sm-12 mb-3">
                                <label class="form-label">NID Document</label>
                                @include('admin.tenants.partials._multifile', ['fieldId' => 'f_nid_document', 'fieldName' => 'nid_document[]', 'existing' => isset($tenant) && $tenant->nid_document])
                            </div>

                            {{-- Driving Licence --}}
                            <div class="col-md-4 col-sm-6 mb-3">
                                <label class="form-label">Driving Licence No</label>
                                <input type="text" name="driving_licence_number" class="form-control" value="{{ old('driving_licence_number', $tenant->driving_licence_number ?? '') }}">
                            </div>
                            <div class="col-md-4 col-sm-6 mb-3">
                                <label class="form-label">Licence Expiry</label>
                                <input type="date" name="driving_licence_expiry" class="form-control" value="{{ old('driving_licence_expiry', $tenant->driving_licence_expiry ?? '') }}">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Licence Document</label>
                                @include('admin.tenants.partials._multifile', ['fieldId' => 'f_driving_licence_document', 'fieldName' => 'driving_licence_document[]', 'existing' => isset($tenant) && $tenant->driving_licence_document])
                            </div>

                            {{-- Passport --}}
                            <div class="col-md-4 col-sm-6 mb-3">
                                <label class="form-label">Passport Number</label>
                                <input type="text" name="passport_number" class="form-control" value="{{ old('passport_number', $tenant->passport_number ?? '') }}">
                            </div>
                            <div class="col-md-4 col-sm-6 mb-3">
                                <label class="form-label">Passport Expiry</label>
                                <input type="date" name="passport_expiry" class="form-control" value="{{ old('passport_expiry', $tenant->passport_expiry ?? '') }}">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Passport Document</label>
                                @include('admin.tenants.partials._multifile', ['fieldId' => 'f_passport_document', 'fieldName' => 'passport_document[]', 'existing' => isset($tenant) && $tenant->passport_document])
                            </div>
                        </div>

                        <!-- ════════════════════════════════════════
                             SECTION 5 · FAMILY / OTHER MEMBERS
                        ════════════════════════════════════════ -->
                        <h4 class="sec-head">5. Family / Other Members of the House</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">#</th>
                                        <th>Name</th>
                                        <th style="width: 100px;">Age</th>
                                        <th>Relation</th>
                                        <th style="width: 150px;">Phone</th>
                                        <th class="text-center" style="width: 50px;">Act</th>
                                    </tr>
                                </thead>
                                <tbody id="members_list"></tbody>
                            </table>
                        </div>
                        <button type="button" class="btn-pf-add mt-2 mb-3" onclick="addMember()">
                            <i class="fa-solid fa-plus"></i> Add Member
                        </button>

                        <!-- ════════════════════════════════════════
                             SECTION 6 · DOMESTIC HELP
                        ════════════════════════════════════════ -->
                        <h4 class="sec-head">6. Domestic Help Information</h4>
                        <div class="row mb-3">
                            <div class="col-md-3 col-sm-6">
                                <label class="form-label">Number of Domestic Helpers</label>
                                <input type="number" id="no_of_help" class="form-control" min="0" value="0">
                            </div>
                        </div>
                        <div id="help_rows"></div>

                        <!-- ════════════════════════════════════════
                             SECTION 7 · DRIVER
                        ════════════════════════════════════════ -->
                        <h4 class="sec-head">7. Driver Information</h4>
                        <div class="row mb-3">
                            <div class="col-md-3 col-sm-6">
                                <label class="form-label">Number of Drivers</label>
                                <input type="number" id="no_of_driver" class="form-control" min="0" value="0">
                            </div>
                        </div>
                        <div id="driver_rows"></div>

                        <!-- ════════════════════════════════════════
                             SECTION 8 · PREVIOUS FLAT DETAILS
                        ════════════════════════════════════════ -->
                        <h4 class="sec-head">8. Previous Flat Details</h4>
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-3">
                                <label class="form-label">Owner Name</label>
                                <input type="text" name="prev_owner_name" class="form-control" value="{{ old('prev_owner_name', $tenant->prev_owner_name ?? '') }}">
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <label class="form-label">Owner Phone Number</label>
                                <input type="text" name="prev_owner_phone" class="form-control" value="{{ old('prev_owner_phone', $tenant->prev_owner_phone ?? '') }}">
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" name="prev_flat_address" class="form-control" value="{{ old('prev_flat_address', $tenant->prev_flat_address ?? '') }}">
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <label class="form-label">Reason of Leaving</label>
                                <input type="text" name="prev_leaving_reason" class="form-control" value="{{ old('prev_leaving_reason', $tenant->prev_leaving_reason ?? '') }}">
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-success"><i class="fa-solid fa-save"></i> Save Tenant Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
/* ─────────────────────────────────────────────────────
   Tenant Image
───────────────────────────────────────────────────── */
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('image_preview');
            var icon    = document.getElementById('upload_icon');
            if (icon) icon.style.display = 'none';
            preview.src = e.target.result;
            preview.style.display = 'inline-block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
(function(){
    var c = document.querySelector('.upload-container');
    if (!c) return;
    c.addEventListener('dragover',  e => { e.preventDefault(); e.stopPropagation(); });
    c.addEventListener('dragleave', e => { e.preventDefault(); e.stopPropagation(); });
    c.addEventListener('drop', function(e) {
        e.preventDefault(); e.stopPropagation();
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            var fi = document.getElementById('tenant_image');
            fi.files = e.dataTransfer.files;
            previewImage(fi);
        }
    });
})();

/* ─────────────────────────────────────────────────────
   MULTI-FILE WIDGET
   Each widget is self-contained; files accumulate in a
   DataTransfer object so the server receives all of them.
───────────────────────────────────────────────────── */
var pfStores = {}; // fieldId → DataTransfer

function pfInitStore(fieldId) {
    if (!pfStores[fieldId]) pfStores[fieldId] = new DataTransfer();
}

function pfAddFiles(fieldId, newFiles) {
    pfInitStore(fieldId);
    var dt = pfStores[fieldId];
    for (var i = 0; i < newFiles.length; i++) dt.items.add(newFiles[i]);
    pfSync(fieldId);
}

function pfRemoveFile(fieldId, index) {
    pfInitStore(fieldId);
    var old = pfStores[fieldId];
    var fresh = new DataTransfer();
    for (var i = 0; i < old.files.length; i++) {
        if (i !== index) fresh.items.add(old.files[i]);
    }
    pfStores[fieldId] = fresh;
    pfSync(fieldId);
}

function pfSync(fieldId) {
    var input   = document.getElementById(fieldId);
    var nameBox = document.getElementById(fieldId + '_names');
    if (!input || !nameBox) return;
    var dt = pfStores[fieldId];
    input.files = dt.files;
    // Render tags
    nameBox.innerHTML = '';
    for (var i = 0; i < dt.files.length; i++) {
        (function(idx, fname){
            var tag = document.createElement('span');
            tag.className = 'pf-file__tag';
            tag.innerHTML = '<span title="'+fname+'">'+fname+'</span>';
            var btn = document.createElement('button');
            btn.type = 'button'; btn.textContent = '✕';
            btn.onclick = function(){ pfRemoveFile(fieldId, idx); };
            tag.appendChild(btn);
            nameBox.appendChild(tag);
        })(i, dt.files[i].name);
    }
}

function pfZoneDragOver(e, el) {
    e.preventDefault(); e.stopPropagation();
    el.classList.add('dragover');
}
function pfZoneDragLeave(e, el) {
    e.preventDefault(); e.stopPropagation();
    el.classList.remove('dragover');
}
function pfZoneDrop(e, el, fieldId) {
    e.preventDefault(); e.stopPropagation();
    el.classList.remove('dragover');
    if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
        pfAddFiles(fieldId, e.dataTransfer.files);
    }
}
function pfZoneChange(input, fieldId) {
    if (input.files && input.files.length > 0) {
        pfAddFiles(fieldId, input.files);
        // Do not clear input.value here to keep the files for submission
    }
}

/* ─────────────────────────────────────────────────────
   MARITAL STATUS / SPOUSE TOGGLE
───────────────────────────────────────────────────── */
function toggleSpouse() {
    var ms = document.getElementById('marital_status');
    var ss = document.getElementById('spouse_section');
    if (ms && ss) ss.style.display = (ms.value === 'married') ? 'block' : 'none';
}

/* ─────────────────────────────────────────────────────
   CHILDREN
───────────────────────────────────────────────────── */
var existingChildren = {!! isset($tenant) && $tenant->children_info ? json_encode($tenant->children_info) : '[]' !!};

function renderChildren() {
    var noChildInput = document.getElementById('no_of_children');
    var childrenRows = document.getElementById('children_rows');
    if (!noChildInput || !childrenRows) return;
    var num = parseInt(noChildInput.value) || 0;
    var html = '';
    for (var i = 0; i < num; i++) {
        var ch = existingChildren[i] || {};
        var bcId = 'child_bc_' + i;
        html += '<div class="pf-child-row" id="child_row_' + i + '">' +
            '<input type="text" name="child_name[]" class="form-control" placeholder="Child Name" value="' + (ch.name || '') + '">' +
            '<select name="child_gender[]" class="form-control">' +
                '<option value="">Gender</option>' +
                '<option value="male"'   + (ch.gender === 'male'   ? ' selected' : '') + '>Male</option>' +
                '<option value="female"' + (ch.gender === 'female' ? ' selected' : '') + '>Female</option>' +
            '</select>' +
            '<input type="date" name="child_dob[]" class="form-control" value="' + (ch.dob || '') + '">' +
            '<div>' + makePfZoneHTML(bcId, 'child_birthcertificate[' + i + '][]') + '</div>' +
            '<button type="button" class="btn-pf-del" onclick="removeChild(' + i + ')" title="Remove">✕</button>' +
        '</div>';
    }
    if (num === 0) html = '<p style="color:#aaa;font-size:.85rem;">Set number of children to enter details.</p>';
    childrenRows.innerHTML = html;
}

function removeChild(i) {
    var noChildInput = document.getElementById('no_of_children');
    if (noChildInput && parseInt(noChildInput.value) > 0) {
        noChildInput.value = parseInt(noChildInput.value) - 1;
        renderChildren();
    }
}

/* ─────────────────────────────────────────────────────
   HELPER: build pf-file zone HTML string
───────────────────────────────────────────────────── */
function makePfZoneHTML(fieldId, fieldName) {
    return '<div class="pf-file" onclick="document.getElementById(\'' + fieldId + '\').click()" ' +
               'ondragover="pfZoneDragOver(event,this)" ondragleave="pfZoneDragLeave(event,this)" ' +
               'ondrop="pfZoneDrop(event,this,\'' + fieldId + '\')">' +
           '<div class="pf-file__icon">' +
               '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>' +
           '</div>' +
           '<div class="pf-file__text">' +
               '<div class="pf-file__cta">Drag & Drop or Click</div>' +
               '<div class="pf-file__hint">Images, PDFs, docs — any size</div>' +
               '<div class="pf-file__names" id="' + fieldId + '_names"></div>' +
           '</div>' +
           '</div>' +
           '<input type="file" id="' + fieldId + '" name="' + fieldName + '" class="d-none" multiple ' +
               'onchange="pfZoneChange(this,\'' + fieldId + '\')">';
}

/* ─────────────────────────────────────────────────────
   OCCUPATION  (job | business)
───────────────────────────────────────────────────── */
var occCount = 0;

function addOccupation() {
    var idx = occCount++;
    var list = document.getElementById('occupation_list');
    var card = document.createElement('div');
    card.className = 'occ-card';
    card.id = 'occ_card_' + idx;

    var delBtn = (idx > 0) ? '<button type="button" class="btn-pf-del ms-auto mt-4" onclick="removeOcc(' + idx + ')">✕</button>' : '';

    card.innerHTML =
        '<div class="row align-items-center mb-2">' +
            '<div class="col-md-4 col-sm-8 mb-3">' +
                '<label class="form-label" style="color:#94a3b8;font-size:.85rem;font-weight:600;">Occupation Type</label>' +
                '<select name="occupation_type[' + idx + ']" id="occ_type_val_' + idx + '" class="form-control" onchange="setOccType(' + idx + ', this.value)">' +
                    '<option value="" selected disabled>Select</option>' +
                    '<option value="job">Job</option>' +
                    '<option value="business">Business</option>' +
                '</select>' +
            '</div>' +
            '<div class="col-md-8 col-sm-4 mb-3 text-end">' + delBtn + '</div>' +
        '</div>' +

        // JOB fields
        '<div id="occ_job_' + idx + '" style="display:none;">' +
            '<div class="row">' +
                '<div class="col-md-4 col-sm-12 mb-3"><label class="form-label">Company Name</label>' +
                    '<input type="text" name="occupation_company[' + idx + ']" class="form-control"></div>' +
                '<div class="col-md-4 col-sm-12 mb-3"><label class="form-label">Address</label>' +
                    '<input type="text" name="occupation_address[' + idx + ']" class="form-control"></div>' +
                '<div class="col-md-4 col-sm-12 mb-3"><label class="form-label">Verification Document <small style="color:#6b7280;">(optional)</small></label>' +
                    makePfZoneHTML('occ_doc_' + idx, 'occupation_document[' + idx + '][]') +
                '</div>' +
            '</div>' +
        '</div>' +

        // BUSINESS fields (hidden by default)
        '<div id="occ_business_' + idx + '" style="display:none;">' +
            '<div class="row">' +
                '<div class="col-md-6 col-sm-12 mb-3"><label class="form-label">Business Name</label>' +
                    '<input type="text" name="business_name[' + idx + ']" class="form-control"></div>' +
                '<div class="col-md-6 col-sm-12 mb-3"><label class="form-label">Business Address</label>' +
                    '<input type="text" name="business_address[' + idx + ']" class="form-control"></div>' +
                '<div class="col-md-4 col-sm-12 mb-3"><label class="form-label">Trade License Document</label>' +
                    makePfZoneHTML('biz_trade_doc_' + idx, 'trade_license_document[' + idx + '][]') +
                '</div>' +
                '<div class="col-md-4 col-sm-12 mb-3"><label class="form-label">TIN Certificate</label>' +
                    makePfZoneHTML('biz_tin_doc_' + idx, 'tin_certificate_document[' + idx + '][]') +
                '</div>' +
                '<div class="col-md-4 col-sm-12 mb-3"><label class="form-label">Bank Solvency / Other Docs</label>' +
                    makePfZoneHTML('biz_other_doc_' + idx, 'business_other_document[' + idx + '][]') +
                '</div>' +
            '</div>' +
        '</div>';

    list.appendChild(card);
}

function setOccType(idx, type) {
    var jobDiv = document.getElementById('occ_job_' + idx);
    var bizDiv = document.getElementById('occ_business_' + idx);
    if (jobDiv) jobDiv.style.display = (type === 'job') ? 'block' : 'none';
    if (bizDiv) bizDiv.style.display = (type === 'business') ? 'block' : 'none';
}

function removeOcc(idx) {
    var el = document.getElementById('occ_card_' + idx);
    if (el) el.remove();
}

/* ─────────────────────────────────────────────────────
   EDUCATION
───────────────────────────────────────────────────── */
var eduCount = 0;

function addEducation() {
    var idx = eduCount++;
    var list = document.getElementById('education_list');
    var wrap = document.createElement('div');
    wrap.id = 'edu_row_' + idx;
    wrap.style.marginBottom = '8px';

    var delBtn = (idx > 0) ? '<button type="button" class="btn-pf-del" onclick="removeEdu(' + idx + ')">✕</button>' : '<div></div>';

    wrap.innerHTML =
        '<div class="pf-row pf-row-4" style="grid-template-columns:1fr 1fr 120px 1fr 32px;">' +
            '<input type="text" name="edu_exam[' + idx + ']" class="form-control" placeholder="Exam / Degree Name">' +
            '<input type="text" name="edu_institution[' + idx + ']" class="form-control" placeholder="Institution">' +
            '<input type="number" name="edu_year[' + idx + ']" class="form-control" placeholder="Passing Year" min="1950" max="2099">' +
            '<div>' + makePfZoneHTML('edu_doc_' + idx, 'edu_document[' + idx + '][]') + '</div>' +
            delBtn +
        '</div>';

    list.appendChild(wrap);
}

function removeEdu(idx) {
    var el = document.getElementById('edu_row_' + idx);
    if (el) el.remove();
}

/* ─────────────────────────────────────────────────────
   FAMILY MEMBERS
───────────────────────────────────────────────────── */
var memberCount = 0;

function addMember() {
    var idx = memberCount++;
    var list = document.getElementById('members_list');
    var tr = document.createElement('tr');
    tr.id = 'member_row_' + idx;

    var delBtn = (idx > 0) ? '<button type="button" class="btn-pf-del mx-auto" onclick="removeMember(' + idx + ')">✕</button>' : '';

    tr.innerHTML =
        '<td class="text-center align-middle" style="color:#6b7280; font-size:.85rem; font-weight:600;">' + (idx + 1) + '</td>' +
        '<td><input type="text" name="member_name[' + idx + ']" class="form-control" placeholder="Name"></td>' +
        '<td><input type="number" name="member_age[' + idx + ']" class="form-control" placeholder="Age" min="0"></td>' +
        '<td><input type="text" name="member_relation[' + idx + ']" class="form-control" placeholder="Relation"></td>' +
        '<td><input type="text" name="member_phone[' + idx + ']" class="form-control" placeholder="Phone"></td>' +
        '<td class="text-center align-middle">' + delBtn + '</td>';

    list.appendChild(tr);
}

function removeMember(idx) {
    var el = document.getElementById('member_row_' + idx);
    if (el) el.remove();
}

/* ─────────────────────────────────────────────────────
   DOMESTIC HELP
───────────────────────────────────────────────────── */
function renderHelp() {
    var num = parseInt(document.getElementById('no_of_help').value) || 0;
    var box = document.getElementById('help_rows');
    box.innerHTML = '';
    for (var i = 0; i < num; i++) {
        var wrap = document.createElement('div');
        wrap.style.marginBottom = '12px';
        wrap.innerHTML =
            '<div class="pf-row" style="grid-template-columns:1fr 1fr 1fr 1fr;gap:8px;">' +
                '<input type="text" name="help_name[]" class="form-control" placeholder="Name">' +
                '<input type="text" name="help_nid[]"  class="form-control" placeholder="NID Number">' +
                '<input type="text" name="help_mobile[]" class="form-control" placeholder="Mobile Number">' +
                '<input type="text" name="help_address[]" class="form-control" placeholder="Permanent Address">' +
            '</div>';
        box.appendChild(wrap);
    }
}

/* ─────────────────────────────────────────────────────
   DRIVER
───────────────────────────────────────────────────── */
function renderDrivers() {
    var num = parseInt(document.getElementById('no_of_driver').value) || 0;
    var box = document.getElementById('driver_rows');
    box.innerHTML = '';
    for (var i = 0; i < num; i++) {
        var wrap = document.createElement('div');
        wrap.style.marginBottom = '12px';
        wrap.innerHTML =
            '<div class="pf-row" style="grid-template-columns:1fr 1fr 1fr 1fr;gap:8px;">' +
                '<input type="text" name="driver_name[]" class="form-control" placeholder="Name">' +
                '<input type="text" name="driver_nid[]"  class="form-control" placeholder="NID Number">' +
                '<input type="text" name="driver_mobile[]" class="form-control" placeholder="Mobile Number">' +
                '<input type="text" name="driver_address[]" class="form-control" placeholder="Permanent Address">' +
            '</div>';
        box.appendChild(wrap);
    }
}

/* ─────────────────────────────────────────────────────
   INIT
───────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    toggleSpouse();
    renderChildren();

    // Default rows
    addOccupation();
    addEducation();
    addMember();

    var noChildInput = document.getElementById('no_of_children');
    if (noChildInput) noChildInput.addEventListener('input', renderChildren);

    var noHelp = document.getElementById('no_of_help');
    if (noHelp) noHelp.addEventListener('input', renderHelp);

    var noDrv = document.getElementById('no_of_driver');
    if (noDrv) noDrv.addEventListener('input', renderDrivers);
});
</script>
@endpush