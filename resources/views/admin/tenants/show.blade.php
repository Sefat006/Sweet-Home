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
                    <li class="list-group-item bg-transparent border-secondary" style="color: black !important;"><i class="fa-solid fa-phone" style="color: black !important;"></i> {{ $tenant->phone }}</li>
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
                    
                    <h6 class="text-primary mb-3">Personal Documents</h6>
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <h6 class="mb-2">NID Document</h6>
                                @if($tenant->nid_document)
                                    <a href="{{ asset('storage/'.$tenant->nid_document) }}" download class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-download"></i> Download</a>
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <h6 class="mb-2">Passport</h6>
                                @if(isset($tenant) && $tenant->passport_document)
                                    <a href="{{ asset('storage/'.$tenant->passport_document) }}" download class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-download"></i> Download</a>
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <h6 class="mb-2">Driving Licence</h6>
                                @if(isset($tenant) && $tenant->driving_licence_document)
                                    <a href="{{ asset('storage/'.$tenant->driving_licence_document) }}" download class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-download"></i> Download</a>
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <h6 class="mb-2">Occupation Doc</h6>
                                @if(isset($tenant) && $tenant->occupation_document)
                                    <a href="{{ asset('storage/'.$tenant->occupation_document) }}" download class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-download"></i> Download</a>
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
                                    <a href="{{ asset('storage/'.$flatTenant->advance_document) }}" download class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-download"></i> Download</a>
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <h6 class="mb-2">Agreement Document</h6>
                                @if($flatTenant->agreement_document)
                                    <a href="{{ asset('storage/'.$flatTenant->agreement_document) }}" download class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-download"></i> Download</a>
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <h6 class="mb-2">Police Form</h6>
                                @if(isset($flatTenant) && $flatTenant->police_form_document)
                                    <a href="{{ asset('storage/'.$flatTenant->police_form_document) }}" download class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-download"></i> Download</a>
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <h6 class="mb-2">Notice Doc</h6>
                                @if(isset($flatTenant) && $flatTenant->notice_document)
                                    <a href="{{ asset('storage/'.$flatTenant->notice_document) }}" download class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-download"></i> Download</a>
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <h6 class="mb-2">House Rent Copy</h6>
                                @if(isset($flatTenant) && $flatTenant->house_rent_copy)
                                    <a href="{{ asset('storage/'.$flatTenant->house_rent_copy) }}" download class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-download"></i> Download</a>
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
