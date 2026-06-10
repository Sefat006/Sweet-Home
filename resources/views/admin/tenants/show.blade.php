@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Tenant Profile - {{ $tenant->name }}</h2>
                        <p style="color:white !important;" class="mb-0">Building: {{ $building->name }} | Flat: {{ $flat->flat_name }}</p>
                    </div>
                </div>
                <div class="breadcrumb__content__right d-flex gap-2">
                    <a href="{{ route('admin.tenants.download', [$building->id, $flat->id, $tenant->id]) }}" class="btn btn-primary">
                        <i class="fa-solid fa-file-pdf"></i> Download Profile PDF
                    </a>
                    <a href="{{ route('admin.tenants.index', [$building->id, $flat->id]) }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card bg-style text-center p-4">
                @if($tenant->image)
                    <img src="{{ asset($tenant->image) }}" class="rounded-circle mx-auto d-block" style="width:150px; height:150px; object-fit:cover;" alt="Profile">
                    <div class="mt-2 mb-3">
                        <a href="{{ asset($tenant->image) }}" download class="btn btn-xs btn-outline-light py-1 px-2" style="font-size: 11px; border-color: rgba(255,255,255,0.2);">
                            <i class="fa-solid fa-download"></i> Download Photo
                        </a>
                    </div>
                @else
                    <img src="{{ asset('admin/assets/images/default-avatar.png') }}" class="rounded-circle mx-auto d-block mb-3" style="width:150px; height:150px; object-fit:cover;" alt="Profile">
                @endif
                <h4 class="mt-3">{{ $tenant->name }}</h4>
                <p class="text-muted">{{ $tenant->tenant_id }}</p>
                
                <ul class="list-group list-group-flush text-start mt-4 bg-transparent">
                    <li class="list-group-item bg-transparent border-secondary" style="color: black !important;"><i class="fa-solid fa-phone" style="color: black !important;"></i> <a href="tel:{{ $tenant->phone }}" style="color: inherit; text-decoration: none;">{{ $tenant->phone }}</a></li>
                    <li class="list-group-item bg-transparent border-secondary" style="color: black !important;"><i class="fa-solid fa-envelope" style="color: black !important;"></i> {{ $tenant->email ?? 'N/A' }}</li>
                    <li class="list-group-item bg-transparent border-secondary" style="color: black !important;"><i class="fa-solid fa-id-card" style="color: black !important;"></i> NID: {{ $tenant->nid_number ?? 'N/A' }}</li>
                    <li class="list-group-item bg-transparent border-secondary" style="color: black !important;"><i class="fa-solid fa-droplet text-danger" style="color: black !important;"></i> Blood: {{ $tenant->blood_group ?? 'N/A' }}</li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card bg-style">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">Details & Documents</h5>
                </div>
                <div class="card-body">
                    <h6 class="text-primary mb-3">Tenancy Information</h6>
                    <div class="row mb-4">
                        <div class="col-sm-4"><p class="mb-1 text-muted">Status</p><strong>{{ ucfirst($flatTenant->status) }}</strong></div>
                        <div class="col-sm-4"><p class="mb-1 text-muted">Start Date</p><strong>{{ $flatTenant->start_date ? $flatTenant->start_date->format('d M, Y') : 'N/A' }}</strong></div>
                        <div class="col-sm-4"><p class="mb-1 text-muted">End Date</p><strong>{{ $flatTenant->end_date ? $flatTenant->end_date->format('d M, Y') : 'Present' }}</strong></div>
                        <div class="col-sm-4 mt-3"><p class="mb-1 text-muted">Advance Amount</p><strong>৳ {{ number_format($flatTenant->advance_amount, 2) }}</strong></div>
                    </div>

                    <h6 class="text-primary mb-3">Personal Details</h6>
                    <div class="row mb-4">
                        <div class="col-sm-4 mb-3"><p class="mb-1 text-muted">Father's Name</p><strong>{{ $tenant->father_name ?? 'N/A' }}</strong></div>
                        <div class="col-sm-4 mb-3"><p class="mb-1 text-muted">Mother's Name</p><strong>{{ $tenant->mother_name ?? 'N/A' }}</strong></div>
                        <div class="col-sm-4 mb-3"><p class="mb-1 text-muted">Gender</p><strong>{{ ucfirst($tenant->gender ?? 'N/A') }}</strong></div>
                        <div class="col-sm-4 mb-3"><p class="mb-1 text-muted">Date of Birth</p><strong>{{ $tenant->dob ? $tenant->dob->format('d M, Y') : 'N/A' }}</strong></div>
                        <div class="col-sm-4 mb-3"><p class="mb-1 text-muted">Religion</p><strong>{{ ucfirst($tenant->religion ?? 'N/A') }}</strong></div>
                        <div class="col-sm-4 mb-3"><p class="mb-1 text-muted">Nationality</p><strong>{{ $tenant->nationality ?? 'N/A' }}</strong></div>
                        <div class="col-sm-6 mb-3"><p class="mb-1 text-muted">Permanent Address</p><strong>{{ $tenant->permanent_address ?? 'N/A' }}</strong></div>
                        <div class="col-sm-6 mb-3"><p class="mb-1 text-muted">Present Address</p><strong>{{ $tenant->present_address ?? 'N/A' }}</strong></div>
                    </div>

                    <h6 class="text-primary mb-3">Emergency Contact Details</h6>
                    <div class="row mb-4">
                        <div class="col-sm-4 mb-3"><p class="mb-1 text-muted">Contact Name</p><strong>{{ $tenant->emergency_contact_name ?? 'N/A' }}</strong></div>
                        <div class="col-sm-4 mb-3"><p class="mb-1 text-muted">Relation</p><strong>{{ $tenant->emergency_contact_relation ?? 'N/A' }}</strong></div>
                        <div class="col-sm-4 mb-3"><p class="mb-1 text-muted">Phone Number</p><strong>@if($tenant->emergency_contact_phone)<a href="tel:{{ $tenant->emergency_contact_phone }}" style="color: inherit; text-decoration: none;">{{ $tenant->emergency_contact_phone }}</a>@else N/A @endif</strong></div>
                        <div class="col-sm-12 mb-3"><p class="mb-1 text-muted">Address</p><strong>{{ $tenant->emergency_contact_address ?? 'N/A' }}</strong></div>
                    </div>

                    @if($tenant->marital_status === 'married')
                    <h6 class="text-primary mb-3">Spouse Information</h6>
                    <div class="row mb-4">
                        <div class="col-sm-4 mb-3"><p class="mb-1 text-muted">Spouse Name</p><strong>{{ $tenant->spouse_name ?? 'N/A' }}</strong></div>
                        <div class="col-sm-4 mb-3"><p class="mb-1 text-muted">Spouse Contact No</p><strong>@if($tenant->spouse_contact_number)<a href="tel:{{ $tenant->spouse_contact_number }}" style="color: inherit; text-decoration: none;">{{ $tenant->spouse_contact_number }}</a>@else N/A @endif</strong></div>
                        <div class="col-sm-4 mb-3"><p class="mb-1 text-muted">Spouse Father's Name</p><strong>{{ $tenant->spouse_father_name ?? 'N/A' }}</strong></div>
                        <div class="col-sm-4 mb-3"><p class="mb-1 text-muted">Spouse Mother's Name</p><strong>{{ $tenant->spouse_mother_name ?? 'N/A' }}</strong></div>
                        <div class="col-sm-4 mb-3"><p class="mb-1 text-muted">Spouse Blood Group</p><strong>{{ $tenant->spouse_blood_group ?? 'N/A' }}</strong></div>
                        <div class="col-sm-4 mb-3"><p class="mb-1 text-muted">Spouse Date of Birth</p><strong>{{ $tenant->spouse_date_of_birth ? $tenant->spouse_date_of_birth->format('d M, Y') : 'N/A' }}</strong></div>
                    </div>
                    @else
                    <h6 class="text-primary mb-3">Marital Status</h6>
                    <div class="row mb-4">
                        <div class="col-sm-4 mb-3"><p class="mb-1 text-muted">Status</p><strong>{{ ucfirst($tenant->marital_status ?? 'single') }}</strong></div>
                    </div>
                    @endif

                    <h6 class="text-primary mb-3">Previous Tenancy Details</h6>
                    <div class="row mb-4">
                        <div class="col-sm-4 mb-3"><p class="mb-1 text-muted">Previous Owner</p><strong>{{ $tenant->prev_owner_name ?? 'N/A' }}</strong></div>
                        <div class="col-sm-4 mb-3"><p class="mb-1 text-muted">Owner Phone</p><strong>@if($tenant->prev_owner_phone)<a href="tel:{{ $tenant->prev_owner_phone }}" style="color: inherit; text-decoration: none;">{{ $tenant->prev_owner_phone }}</a>@else N/A @endif</strong></div>
                        <div class="col-sm-4 mb-3"><p class="mb-1 text-muted">Reason of Leaving</p><strong>{{ $tenant->prev_leaving_reason ?? 'N/A' }}</strong></div>
                        <div class="col-sm-12 mb-3"><p class="mb-1 text-muted">Previous Address</p><strong>{{ $tenant->prev_flat_address ?? 'N/A' }}</strong></div>
                    </div>

                    @if($tenant->notes || $flatTenant->notes)
                    <h6 class="text-primary mb-3">Notes & Remarks</h6>
                    <div class="row mb-4">
                        @if($tenant->notes)
                            <div class="col-sm-6 mb-3"><p class="mb-1 text-muted">Tenant Profile Notes</p><p class="mb-0 p-2 rounded border" style="background: rgba(255,255,255,0.05);">{{ $tenant->notes }}</p></div>
                        @endif
                        @if($flatTenant->notes)
                            <div class="col-sm-6 mb-3"><p class="mb-1 text-muted">Assignment/Tenancy Notes</p><p class="mb-0 p-2 rounded border" style="background: rgba(255,255,255,0.05);">{{ $flatTenant->notes }}</p></div>
                        @endif
                    </div>
                    @endif
                    
                    @if(isset($tenant->occupation_info) && is_array($tenant->occupation_info) && count($tenant->occupation_info) > 0)
                    <h6 class="text-primary mb-3">Occupation Information</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Type</th>
                                    <th>Company/Business</th>
                                    <th>Address</th>
                                    <th>Documents</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tenant->occupation_info as $occ)
                                <tr>
                                    <td>{{ ucfirst($occ['type'] ?? 'N/A') }}</td>
                                    <td>{{ $occ['type'] === 'job' ? ($occ['company'] ?? 'N/A') : ($occ['business_name'] ?? 'N/A') }}</td>
                                    <td>{{ $occ['type'] === 'job' ? ($occ['address'] ?? 'N/A') : ($occ['business_address'] ?? 'N/A') }}</td>
                                    <td>
                                        @if(($occ['type'] ?? '') === 'job')
                                            @if(!empty($occ['documents']) && is_array($occ['documents']))
                                                <div class="d-flex flex-wrap gap-1 justify-content-center">
                                                    @foreach($occ['documents'] as $doc)
                                                        @if($doc)
                                                            <a href="{{ asset($doc) }}" download class="btn btn-outline-primary btn-sm my-1 py-1 px-2" style="font-size: 11px;">
                                                                <i class="fa-solid fa-download"></i> Job Doc
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted" style="font-size: 11px;">No Docs</span>
                                            @endif
                                        @elseif(($occ['type'] ?? '') === 'business')
                                            @php
                                                $hasDocs = !empty($occ['trade_docs']) || !empty($occ['tin_docs']) || !empty($occ['other_docs']);
                                            @endphp
                                            @if($hasDocs)
                                                <div class="d-flex flex-wrap gap-1 justify-content-center">
                                                    @if(!empty($occ['trade_docs']) && is_array($occ['trade_docs']))
                                                        @foreach($occ['trade_docs'] as $doc)
                                                            @if($doc)
                                                                <a href="{{ asset($doc) }}" download class="btn btn-outline-primary btn-sm my-1 py-1 px-2" style="font-size: 11px;">
                                                                    <i class="fa-solid fa-download"></i> Trade Lic
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    @if(!empty($occ['tin_docs']) && is_array($occ['tin_docs']))
                                                        @foreach($occ['tin_docs'] as $doc)
                                                            @if($doc)
                                                                <a href="{{ asset($doc) }}" download class="btn btn-outline-primary btn-sm my-1 py-1 px-2" style="font-size: 11px;">
                                                                    <i class="fa-solid fa-download"></i> TIN
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    @if(!empty($occ['other_docs']) && is_array($occ['other_docs']))
                                                        @foreach($occ['other_docs'] as $doc)
                                                            @if($doc)
                                                                <a href="{{ asset($doc) }}" download class="btn btn-outline-primary btn-sm my-1 py-1 px-2" style="font-size: 11px;">
                                                                    <i class="fa-solid fa-download"></i> Biz Doc
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted" style="font-size: 11px;">No Docs</span>
                                            @endif
                                        @else
                                            <span class="text-muted" style="font-size: 11px;">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    @if(isset($tenant->members_info) && is_array($tenant->members_info) && count($tenant->members_info) > 0)
                    <h6 class="text-primary mb-3">Family Members</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm text-center">
                            <thead class="table-light">
                                <tr><th>Name</th><th>Age</th><th>Relation</th><th>Phone</th></tr>
                            </thead>
                            <tbody>
                                @foreach($tenant->members_info as $mem)
                                <tr>
                                    <td>{{ $mem['name'] ?? 'N/A' }}</td>
                                    <td>{{ $mem['age'] ?? 'N/A' }}</td>
                                    <td>{{ $mem['relation'] ?? 'N/A' }}</td>
                                    <td>@if(!empty($mem['phone']))<a href="tel:{{ $mem['phone'] }}" style="color: inherit; text-decoration: none;">{{ $mem['phone'] }}</a>@else N/A @endif</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    @if(isset($tenant->children_info) && is_array($tenant->children_info) && count($tenant->children_info) > 0)
                    <h6 class="text-primary mb-3">Children</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Date of Birth</th>
                                    <th>Birth Certificate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tenant->children_info as $child)
                                <tr>
                                    <td>{{ $child['name'] ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($child['gender'] ?? 'N/A') }}</td>
                                    <td>{{ $child['dob'] ?? 'N/A' }}</td>
                                    <td>
                                        @if(!empty($child['birthcertificate']) && is_array($child['birthcertificate']))
                                            <div class="d-flex flex-wrap gap-1 justify-content-center">
                                                @foreach($child['birthcertificate'] as $doc)
                                                    @if($doc)
                                                        <a href="{{ asset($doc) }}" download class="btn btn-outline-primary btn-sm my-1 py-1 px-2" style="font-size: 11px;">
                                                            <i class="fa-solid fa-download"></i> BC Doc
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted" style="font-size: 11px;">No Docs</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    
                    @if(isset($tenant->education_info) && is_array($tenant->education_info) && count($tenant->education_info) > 0)
                    <h6 class="text-primary mb-3">Education Information</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Degree/Exam</th>
                                    <th>Institution</th>
                                    <th>Passing Year</th>
                                    <th>Documents</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tenant->education_info as $edu)
                                <tr>
                                    <td>{{ $edu['exam'] ?? 'N/A' }}</td>
                                    <td>{{ $edu['institution'] ?? 'N/A' }}</td>
                                    <td>{{ $edu['year'] ?? 'N/A' }}</td>
                                    <td>
                                        @if(!empty($edu['documents']) && is_array($edu['documents']))
                                            <div class="d-flex flex-wrap gap-1 justify-content-center">
                                                @foreach($edu['documents'] as $doc)
                                                    @if($doc)
                                                        <a href="{{ asset($doc) }}" download class="btn btn-outline-primary btn-sm my-1 py-1 px-2" style="font-size: 11px;">
                                                            <i class="fa-solid fa-download"></i> Edu Doc
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted" style="font-size: 11px;">No Docs</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    @if(isset($tenant->help_info) && is_array($tenant->help_info) && count($tenant->help_info) > 0)
                    <h6 class="text-primary mb-3">Domestic Help</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm text-center">
                            <thead class="table-light">
                                <tr><th>Name</th><th>NID</th><th>Mobile</th><th>Address</th></tr>
                            </thead>
                            <tbody>
                                @foreach($tenant->help_info as $help)
                                <tr>
                                    <td>{{ $help['name'] ?? 'N/A' }}</td>
                                    <td>{{ $help['nid'] ?? 'N/A' }}</td>
                                    <td>@if(!empty($help['mobile']))<a href="tel:{{ $help['mobile'] }}" style="color: inherit; text-decoration: none;">{{ $help['mobile'] }}</a>@else N/A @endif</td>
                                    <td>{{ $help['address'] ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    @if(isset($tenant->driver_info) && is_array($tenant->driver_info) && count($tenant->driver_info) > 0)
                    <h6 class="text-primary mb-3">Drivers</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm text-center">
                            <thead class="table-light">
                                <tr><th>Name</th><th>NID</th><th>Mobile</th><th>Address</th></tr>
                            </thead>
                            <tbody>
                                @foreach($tenant->driver_info as $driver)
                                <tr>
                                    <td>{{ $driver['name'] ?? 'N/A' }}</td>
                                    <td>{{ $driver['nid'] ?? 'N/A' }}</td>
                                    <td>@if(!empty($driver['mobile']))<a href="tel:{{ $driver['mobile'] }}" style="color: inherit; text-decoration: none;">{{ $driver['mobile'] }}</a>@else N/A @endif</td>
                                    <td>{{ $driver['address'] ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <h6 class="text-primary mb-3">Personal Documents</h6>
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <h6 class="mb-2">NID Document</h6>
                                @if(isset($tenant) && $tenant->nid_document && is_array($tenant->nid_document))
                                    @foreach($tenant->nid_document as $doc)
                                        <a href="{{ asset($doc) }}" download class="btn btn-outline-primary btn-sm mb-1"><i class="fa-solid fa-download"></i> Download</a>
                                    @endforeach
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <h6 class="mb-2">Passport</h6>
                                @if(isset($tenant) && $tenant->passport_document && is_array($tenant->passport_document))
                                    @foreach($tenant->passport_document as $doc)
                                        <a href="{{ asset($doc) }}" download class="btn btn-outline-primary btn-sm mb-1"><i class="fa-solid fa-download"></i> Download</a>
                                    @endforeach
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <h6 class="mb-2">Driving Licence</h6>
                                @if(isset($tenant) && $tenant->driving_licence_document && is_array($tenant->driving_licence_document))
                                    @foreach($tenant->driving_licence_document as $doc)
                                        <a href="{{ asset($doc) }}" download class="btn btn-outline-primary btn-sm mb-1"><i class="fa-solid fa-download"></i> Download</a>
                                    @endforeach
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <h6 class="text-primary mb-3">Assignment Documents</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <h6 class="mb-2">Advance Document</h6>
                                @if(isset($flatTenant) && $flatTenant->advance_document && is_array($flatTenant->advance_document))
                                    @foreach($flatTenant->advance_document as $doc)
                                        <a href="{{ asset($doc) }}" download class="btn btn-outline-primary btn-sm mb-1"><i class="fa-solid fa-download"></i> Download</a>
                                    @endforeach
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <h6 class="mb-2">Agreement Document</h6>
                                @if(isset($flatTenant) && $flatTenant->agreement_document && is_array($flatTenant->agreement_document))
                                    @foreach($flatTenant->agreement_document as $doc)
                                        <a href="{{ asset($doc) }}" download class="btn btn-outline-primary btn-sm mb-1"><i class="fa-solid fa-download"></i> Download</a>
                                    @endforeach
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <h6 class="mb-2">Police Form</h6>
                                @if(isset($flatTenant) && $flatTenant->police_form_document && is_array($flatTenant->police_form_document))
                                    @foreach($flatTenant->police_form_document as $doc)
                                        <a href="{{ asset($doc) }}" download class="btn btn-outline-primary btn-sm mb-1"><i class="fa-solid fa-download"></i> Download</a>
                                    @endforeach
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <h6 class="mb-2">Notice Doc</h6>
                                @if(isset($flatTenant) && $flatTenant->notice_document && is_array($flatTenant->notice_document))
                                    @foreach($flatTenant->notice_document as $doc)
                                        <a href="{{ asset($doc) }}" download class="btn btn-outline-primary btn-sm mb-1"><i class="fa-solid fa-download"></i> Download</a>
                                    @endforeach
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <h6 class="mb-2">House Rent Copy</h6>
                                @if(isset($flatTenant) && $flatTenant->house_rent_copy && is_array($flatTenant->house_rent_copy))
                                    @foreach($flatTenant->house_rent_copy as $doc)
                                        <a href="{{ asset($doc) }}" download class="btn btn-outline-primary btn-sm mb-1"><i class="fa-solid fa-download"></i> Download</a>
                                    @endforeach
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <h6 class="text-primary mt-4 mb-3">Generated Bills</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Month</th>
                                    <th>Total (৳)</th>
                                    <th>Paid (৳)</th>
                                    <th>Due (৳)</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bills as $bill)
                                <tr>
                                    <td>{{ $bill->bill_month }} {{ $bill->bill_year }}</td>
                                    <td>{{ number_format($bill->total_amount + $bill->previous_due, 2) }}</td>
                                    <td class="text-success">{{ number_format($bill->paid_amount, 2) }}</td>
                                    <td class="text-danger">{{ number_format($bill->remaining_amount, 2) }}</td>
                                    <td>
                                        @if($bill->collection_status === 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($bill->collection_status === 'partial')
                                            <span class="badge bg-warning text-dark">Partial</span>
                                        @else
                                            <span class="badge bg-danger">Unpaid</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.bills.show', [$building->id, $flat->id, $bill->id]) }}" class="btn btn-outline-primary btn-sm" title="View / Download Bill">
                                            <i class="fa-solid fa-file-invoice"></i> View / Download
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-muted py-3">No bills generated yet for this tenant.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
