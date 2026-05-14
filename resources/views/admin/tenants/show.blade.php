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
                    <img src="{{ asset('storage/'.$tenant->image) }}" class="rounded-circle mx-auto d-block" style="width:150px; height:150px; object-fit:cover;" alt="Profile">
                @else
                    <img src="{{ asset('admin/assets/images/default-avatar.png') }}" class="rounded-circle mx-auto d-block" style="width:150px; height:150px; object-fit:cover;" alt="Profile">
                @endif
                <h4 class="mt-3">{{ $tenant->name }}</h4>
                <p class="text-muted">{{ $tenant->tenant_id }}</p>
                
                <ul class="list-group list-group-flush text-start mt-4 bg-transparent">
                    <li class="list-group-item bg-transparent text-white border-secondary"><i class="fa-solid fa-phone"></i> {{ $tenant->phone }}</li>
                    <li class="list-group-item bg-transparent text-white border-secondary"><i class="fa-solid fa-envelope"></i> {{ $tenant->email ?? 'N/A' }}</li>
                    <li class="list-group-item bg-transparent text-white border-secondary"><i class="fa-solid fa-id-card"></i> NID: {{ $tenant->nid_number ?? 'N/A' }}</li>
                    <li class="list-group-item bg-transparent text-white border-secondary"><i class="fa-solid fa-droplet text-danger"></i> Blood: {{ $tenant->blood_group ?? 'N/A' }}</li>
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
                    
                    <h6 class="text-primary mb-3">Personal Documents</h6>
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <h6 class="mb-2">NID Document</h6>
                                @if($tenant->nid_document)
                                    <a href="{{ asset('storage/'.$tenant->nid_document) }}" target="_blank" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-eye"></i> View File</a>
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
                                @if($flatTenant->advance_document)
                                    <a href="{{ asset('storage/'.$flatTenant->advance_document) }}" target="_blank" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-eye"></i> View File</a>
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <h6 class="mb-2">Agreement Document</h6>
                                @if($flatTenant->agreement_document)
                                    <a href="{{ asset('storage/'.$flatTenant->agreement_document) }}" target="_blank" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-eye"></i> View File</a>
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
