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
    .pf-file {
        border: 2px dashed var(--pf-border);
        border-radius: 8px;
        padding: 13px 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: border-color .15s, background .15s;
        background: rgba(255,255,255,0.05);
        min-height: 56px;
    }
    .pf-file:hover {
        border-color: var(--pf-accent);
        background: rgba(37, 99, 235, .1);
    }
    .pf-file__icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(37, 99, 235, .2);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: background .15s;
    }
    .pf-file:hover .pf-file__icon {
        background: rgba(37, 99, 235, .3);
    }
    .pf-file__icon svg {
        width: 17px;
        height: 17px;
        stroke: var(--pf-accent);
    }
    .pf-file__text {
        flex: 1;
        min-width: 0;
    }
    .pf-file__cta {
        font-size: .8rem;
        font-weight: 600;
        color: var(--pf-accent);
        line-height: 1.3;
    }
    .pf-file__hint {
        font-size: .73rem;
        color: #999;
        line-height: 1.3;
    }
    .pf-file__existing {
        font-size: .73rem;
        color: #16a34a;
        font-weight: 500;
        margin-top: 2px;
    }
    .pf-file__name {
        font-size: .78rem;
        color: #fff;
        font-weight: 500;
        margin-top: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: none;
    }
    .pf-file__name.show {
        display: block;
    }
    
    /* Children rows */
    .pf-edu-row {
        display: grid;
        grid-template-columns: 2fr 2fr 2fr 32px;
        gap: 8px;
        margin-bottom: 8px;
        align-items: center;
    }
    @media(max-width:768px) {
        .pf-edu-row {
            grid-template-columns: 1fr 1fr;
        }
        .pf-edu-row .btn-pf-del {
            grid-column: span 2;
            justify-self: start;
        }
    }
    .btn-pf-del {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        background: #fee2e2;
        border: none;
        color: var(--pf-danger);
        font-size: .85rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
</style>

    @endpush


@section('content')
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
                        
                        <h4 class="mb-3 text-white border-bottom pb-2">1. Personal Information</h4>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Phone *</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            </div>
                            
                            <div class="col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Profile Image (Drag & Drop)</label>
                                <div class="upload-container text-center border rounded p-3" style="border: 2px dashed #ccc !important; cursor: pointer; background: rgba(255,255,255,0.05);" onclick="document.getElementById('tenant_image').click()">
                                    @if(isset($tenant) && $tenant->image)
                                        <img src="{{ asset('storage/'.$tenant->image) }}" id="image_preview" style="max-height: 100px; max-width: 100%; border-radius: 8px;">
                                        <div id="upload_icon" style="display:none;"><i class="fa-solid fa-cloud-arrow-up fa-2x text-secondary"></i><p class="mt-2 mb-0 text-white">Click or drag image here</p></div>
                                    @else
                                        <div id="upload_icon"><i class="fa-solid fa-cloud-arrow-up fa-2x text-secondary"></i><p class="mt-2 mb-0 text-white">Click or drag image here</p></div>
                                        <img src="" id="image_preview" style="max-height: 100px; max-width: 100%; border-radius: 8px; display: none;">
                                    @endif
                                </div>
                                <input type="file" name="image" id="tenant_image" class="d-none" accept="image/*" onchange="previewImage(this)">
                            </div>
    
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">NID Number</label>
                                <input type="text" name="nid_number" class="form-control" value="{{ old('nid_number') }}">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">NID Document</label>
        <div class="pf-file" onclick="document.getElementById('f_nid_document').click()" ondragover="pfDragOver(event, this)" ondragleave="pfDragLeave(event, this)" ondrop="pfDrop(event, this, 'f_nid_document')">
            <div class="pf-file__icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 002.112 2.13" /></svg>
            </div>
            <div class="pf-file__text">
                <div class="pf-file__cta">Drag & Drop or Click to Attach</div>
                @if(isset($tenant) && $tenant->nid_document)
                <div class="pf-file__existing">&#10003; Current file uploaded</div>
                @endif
                <div class="pf-file__name" id="f_nid_document_name"></div>
            </div>
        </div>
        <input type="file" id="f_nid_document" name="nid_document" class="d-none" onchange="pfFile(this,'f_nid_document_name')">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" value="{{ old('dob') }}">
                            </div>
                            
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Marital Status</label>
                                <select name="marital_status" class="form-control" id="marital_status" onchange="toggleSpouse()">
                                    <option value="">Select Status</option>
                                    <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>
                            <!-- Spouse & Children Section -->
                            <div class="col-12" id="spouse_section" style="display: none;">
                                <h5 class="mt-3 mb-2 text-white border-bottom pb-1">Spouse Information</h5>
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

                                    <div class="col-12 mt-4">
                                        <h5 class="mt-3 mb-2 text-white border-bottom pb-1">Children Information</h5>
                                        <div class="row">
                                            <div class="col-md-4 col-sm-12 mb-3">
                                                <label class="form-label">Number of Children</label>
                                                <input type="number" name="no_of_children" id="no_of_children" class="form-control" min="0" value="{{ old('no_of_children', $tenant->no_of_children ?? 0) }}">
                                            </div>
                                        </div>
                                        <div id="children_rows">
                                            <!-- JS will populate rows -->
                                        </div>
                                    </div>
    
                            </div>
    
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Blood Group</label>
                                <input type="text" name="blood_group" class="form-control" value="{{ old('blood_group') }}" placeholder="e.g. O+">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Religion</label>
                                <input type="text" name="religion" class="form-control" value="{{ old('religion', $tenant->religion ?? '') }}">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Nationality</label>
                                <input type="text" name="nationality" class="form-control" value="{{ old('nationality', $tenant->nationality ?? '') }}">
                            </div>
    
                            <div class="col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Occupation</label>
                                <input type="text" name="occupation" class="form-control" value="{{ old('occupation') }}">
                            </div>
                            <div class="col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Occupation Document</label>
        <div class="pf-file" onclick="document.getElementById('f_occupation_document').click()" ondragover="pfDragOver(event, this)" ondragleave="pfDragLeave(event, this)" ondrop="pfDrop(event, this, 'f_occupation_document')">
            <div class="pf-file__icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 002.112 2.13" /></svg>
            </div>
            <div class="pf-file__text">
                <div class="pf-file__cta">Drag & Drop or Click to Attach</div>
                @if(isset($tenant) && $tenant->occupation_document)
                <div class="pf-file__existing">&#10003; Current file uploaded</div>
                @endif
                <div class="pf-file__name" id="f_occupation_document_name"></div>
            </div>
        </div>
        <input type="file" id="f_occupation_document" name="occupation_document" class="d-none" onchange="pfFile(this,'f_occupation_document_name')">
                            </div>
                        </div>

                        
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Passport Number</label>
                                <input type="text" name="passport_number" class="form-control" value="{{ old('passport_number', $tenant->passport_number ?? '') }}">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Passport Expiry</label>
                                <input type="date" name="passport_expiry" class="form-control" value="{{ old('passport_expiry', $tenant->passport_expiry ?? '') }}">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Passport Document</label>
        <div class="pf-file" onclick="document.getElementById('f_passport_document').click()" ondragover="pfDragOver(event, this)" ondragleave="pfDragLeave(event, this)" ondrop="pfDrop(event, this, 'f_passport_document')">
            <div class="pf-file__icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 002.112 2.13" /></svg>
            </div>
            <div class="pf-file__text">
                <div class="pf-file__cta">Drag & Drop or Click to Attach</div>
                @if(isset($tenant) && $tenant->passport_document)
                <div class="pf-file__existing">&#10003; Current file uploaded</div>
                @endif
                <div class="pf-file__name" id="f_passport_document_name"></div>
            </div>
        </div>
        <input type="file" id="f_passport_document" name="passport_document" class="d-none" onchange="pfFile(this,'f_passport_document_name')">
                            </div>

                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Driving Licence No</label>
                                <input type="text" name="driving_licence_number" class="form-control" value="{{ old('driving_licence_number', $tenant->driving_licence_number ?? '') }}">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Licence Expiry</label>
                                <input type="date" name="driving_licence_expiry" class="form-control" value="{{ old('driving_licence_expiry', $tenant->driving_licence_expiry ?? '') }}">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Licence Document</label>
        <div class="pf-file" onclick="document.getElementById('f_driving_licence_document').click()" ondragover="pfDragOver(event, this)" ondragleave="pfDragLeave(event, this)" ondrop="pfDrop(event, this, 'f_driving_licence_document')">
            <div class="pf-file__icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 002.112 2.13" /></svg>
            </div>
            <div class="pf-file__text">
                <div class="pf-file__cta">Drag & Drop or Click to Attach</div>
                @if(isset($tenant) && $tenant->driving_licence_document)
                <div class="pf-file__existing">&#10003; Current file uploaded</div>
                @endif
                <div class="pf-file__name" id="f_driving_licence_document_name"></div>
            </div>
        </div>
        <input type="file" id="f_driving_licence_document" name="driving_licence_document" class="d-none" onchange="pfFile(this,'f_driving_licence_document_name')">
                            </div>
                            <div class="col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Occupation Company</label>
                                <input type="text" name="occupation_company" class="form-control" value="{{ old('occupation_company', $tenant->occupation_company ?? '') }}">
                            </div>
                            <div class="col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Occupation Address</label>
                                <textarea name="occupation_address" class="form-control" rows="1">{{ old('occupation_address', $tenant->occupation_address ?? '') }}</textarea>
                            </div>
    
                        <h4 class="mb-3 text-white border-bottom pb-2 mt-4">2. Addresses & Contact</h4>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Present Address</label>
                                <textarea name="present_address" class="form-control" rows="3">{{ old('present_address') }}</textarea>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Permanent Address</label>
                                <textarea name="permanent_address" class="form-control" rows="3">{{ old('permanent_address') }}</textarea>
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Emergency Contact Name</label>
                                <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Emergency Contact Phone</label>
                                <input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone') }}">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Relation</label>
                                <input type="text" name="emergency_contact_relation" class="form-control" value="{{ old('emergency_contact_relation') }}">
                            </div>
                        </div>

                        <h4 class="mb-3 text-white border-bottom pb-2 mt-4">3. Flat Assignment Details</h4>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Start Date *</label>
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Advance Amount</label>
                                <input type="number" step="0.01" name="advance_amount" class="form-control" value="{{ old('advance_amount') }}" placeholder="0.00">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Advance Document</label>
        <div class="pf-file" onclick="document.getElementById('f_advance_document').click()" ondragover="pfDragOver(event, this)" ondragleave="pfDragLeave(event, this)" ondrop="pfDrop(event, this, 'f_advance_document')">
            <div class="pf-file__icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 002.112 2.13" /></svg>
            </div>
            <div class="pf-file__text">
                <div class="pf-file__cta">Drag & Drop or Click to Attach</div>
                @if(isset($flatTenant) && $flatTenant->advance_document)
                <div class="pf-file__existing">&#10003; Current file uploaded</div>
                @endif
                <div class="pf-file__name" id="f_advance_document_name"></div>
            </div>
        </div>
        <input type="file" id="f_advance_document" name="advance_document" class="d-none" onchange="pfFile(this,'f_advance_document_name')">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Agreement Document</label>
        <div class="pf-file" onclick="document.getElementById('f_agreement_document').click()" ondragover="pfDragOver(event, this)" ondragleave="pfDragLeave(event, this)" ondrop="pfDrop(event, this, 'f_agreement_document')">
            <div class="pf-file__icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 002.112 2.13" /></svg>
            </div>
            <div class="pf-file__text">
                <div class="pf-file__cta">Drag & Drop or Click to Attach</div>
                @if(isset($flatTenant) && $flatTenant->agreement_document)
                <div class="pf-file__existing">&#10003; Current file uploaded</div>
                @endif
                <div class="pf-file__name" id="f_agreement_document_name"></div>
            </div>
        </div>
        <input type="file" id="f_agreement_document" name="agreement_document" class="d-none" onchange="pfFile(this,'f_agreement_document_name')">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Police Form Document</label>
        <div class="pf-file" onclick="document.getElementById('f_police_form_document').click()" ondragover="pfDragOver(event, this)" ondragleave="pfDragLeave(event, this)" ondrop="pfDrop(event, this, 'f_police_form_document')">
            <div class="pf-file__icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 002.112 2.13" /></svg>
            </div>
            <div class="pf-file__text">
                <div class="pf-file__cta">Drag & Drop or Click to Attach</div>
                @if(isset($flatTenant) && $flatTenant->police_form_document)
                <div class="pf-file__existing">&#10003; Current file uploaded</div>
                @endif
                <div class="pf-file__name" id="f_police_form_document_name"></div>
            </div>
        </div>
        <input type="file" id="f_police_form_document" name="police_form_document" class="d-none" onchange="pfFile(this,'f_police_form_document_name')">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">Notice Document</label>
        <div class="pf-file" onclick="document.getElementById('f_notice_document').click()" ondragover="pfDragOver(event, this)" ondragleave="pfDragLeave(event, this)" ondrop="pfDrop(event, this, 'f_notice_document')">
            <div class="pf-file__icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 002.112 2.13" /></svg>
            </div>
            <div class="pf-file__text">
                <div class="pf-file__cta">Drag & Drop or Click to Attach</div>
                @if(isset($flatTenant) && $flatTenant->notice_document)
                <div class="pf-file__existing">&#10003; Current file uploaded</div>
                @endif
                <div class="pf-file__name" id="f_notice_document_name"></div>
            </div>
        </div>
        <input type="file" id="f_notice_document" name="notice_document" class="d-none" onchange="pfFile(this,'f_notice_document_name')">
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label class="form-label">House Rent Copy</label>
        <div class="pf-file" onclick="document.getElementById('f_house_rent_copy').click()" ondragover="pfDragOver(event, this)" ondragleave="pfDragLeave(event, this)" ondrop="pfDrop(event, this, 'f_house_rent_copy')">
            <div class="pf-file__icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 002.112 2.13" /></svg>
            </div>
            <div class="pf-file__text">
                <div class="pf-file__cta">Drag & Drop or Click to Attach</div>
                @if(isset($flatTenant) && $flatTenant->house_rent_copy)
                <div class="pf-file__existing">&#10003; Current file uploaded</div>
                @endif
                <div class="pf-file__name" id="f_house_rent_copy_name"></div>
            </div>
        </div>
        <input type="file" id="f_house_rent_copy" name="house_rent_copy" class="d-none" onchange="pfFile(this,'f_house_rent_copy_name')">
                            </div>
                        </div>
                        
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-success"><i class="fa-solid fa-save"></i> Save & Enroll</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    @push('scripts')
    <script>
        function toggleSpouse() {
            var ms = document.getElementById('marital_status');
            if(ms && ms.value === 'married') {
                document.getElementById('spouse_section').style.display = 'block';
            } else {
                var el = document.getElementById('spouse_section');
                if(el) el.style.display = 'none';
            }
        }
        document.addEventListener('DOMContentLoaded', toggleSpouse);
    </script>
    
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = document.getElementById('image_preview');
                    var icon = document.getElementById('upload_icon');
                    if (icon) icon.style.display = 'none';
                    preview.src = e.target.result;
                    preview.style.display = 'inline-block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Drag and drop events
        var container = document.querySelector('.upload-container');
        if (container) {
            container.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                container.style.borderColor = '#007bff !important';
            });
            container.addEventListener('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                container.style.borderColor = '#ccc !important';
            });
            container.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                container.style.borderColor = '#ccc !important';
                if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                    var fileInput = document.getElementById('tenant_image');
                    fileInput.files = e.dataTransfer.files;
                    previewImage(fileInput);
                }
            });
        }
    </script>
    
    <script>
        function pfFile(input, nameId) {
            const el = document.getElementById(nameId);
            if(input.files && input.files[0]) {
                el.textContent = input.files[0].name;
                el.classList.add("show");
            } else {
                el.textContent = "";
                el.classList.remove("show");
            }
        }
        function pfDragOver(e, el) {
            e.preventDefault(); e.stopPropagation();
            el.style.borderColor = "#2563eb";
        }
        function pfDragLeave(e, el) {
            e.preventDefault(); e.stopPropagation();
            el.style.borderColor = "var(--pf-border)";
        }
        function pfDrop(e, el, inputId) {
            e.preventDefault(); e.stopPropagation();
            el.style.borderColor = "var(--pf-border)";
            if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                let input = document.getElementById(inputId);
                input.files = e.dataTransfer.files;
                pfFile(input, inputId + "_name");
            }
        }
        
        // Children Logic
        const existingChildren = {!! isset($tenant) && $tenant->children_info ? json_encode($tenant->children_info) : '[]' !!};
        const noChildInput = document.getElementById("no_of_children");
        const childrenRows = document.getElementById("children_rows");
        
        function renderChildren() {
            if(!noChildInput || !childrenRows) return;
            const num = parseInt(noChildInput.value) || 0;
            let html = "";
            for(let i=0; i<num; i++) {
                let ch = existingChildren[i] || {};
                html += `
                <div class="pf-edu-row">
                    <input type="text" name="child_name[]" class="form-control" placeholder="Child Name" value="${ch.name || ''}">
                    <select name="child_gender[]" class="form-control">
                        <option value="">Select Gender</option>
                        <option value="male" ${ch.gender === 'male' ? 'selected' : ''}>Male</option>
                        <option value="female" ${ch.gender === 'female' ? 'selected' : ''}>Female</option>
                    </select>
                    <input type="date" name="child_dob[]" class="form-control" value="${ch.dob || ''}">
                    <button type="button" class="btn-pf-del" onclick="removeBtn(this)" title="Remove">✕</button>
                </div>`;
            }
            if(num === 0) {
                html = `<p class="pf-empty-msg" style="color:#aaa;">Set number of children to enter details.</p>`;
            }
            childrenRows.innerHTML = html;
        }
        
        function removeBtn(btn) {
            if(noChildInput.value > 0) {
                noChildInput.value = parseInt(noChildInput.value) - 1;
                renderChildren();
            }
        }
        
        if(noChildInput) {
            noChildInput.addEventListener("input", renderChildren);
        }
        
        document.addEventListener("DOMContentLoaded", function() {
            if(typeof toggleSpouse !== "undefined") toggleSpouse();
            renderChildren();
        });
    </script>
    
@endpush
    
@endsection
