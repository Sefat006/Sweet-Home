@extends('admin.layouts.app')

@section('content')

<style>
    .container-fluid {
        color: white;
    }
    .breadcrumb__title h2 {
        color: white;
    }
    .card-header {
        color: #000;
    }
    .card-body, .card-body div, .card-body strong, .card-body span {
        color: #000;
    }
    .text-muted {
        color: #444;
    }
    h5 {
        color: #000;
        font-weight: 700;
    }
</style>

<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>Building Information</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <a href="{{ route('admin.building.edit', $building->id) }}" class="btn btn-blue">Edit Building</a>
                    <a href="{{ route('admin.building.index', ['admin_id' => $building->user_id]) }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                <div class="card-header" style="background-color: #f8fafc; font-weight: 700; padding: 15px; border-bottom: 1px solid #e2e8f0;">
                    Basic Information
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Building Name:</strong></div>
                        <div class="col-md-9">{{ $building->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Logo:</strong></div>
                        <div class="col-md-9">
                            @if($building->logo)
                                <img src="{{ asset('storage/' . $building->logo) }}" width="150" style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 5px;" alt="Logo">
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>No of Floors:</strong></div>
                        <div class="col-md-9">{{ $building->no_of_floor }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Address:</strong></div>
                        <div class="col-md-9">{{ $building->address }}</div>
                    </div>
                </div>
            </div>

            <div class="card mb-4" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                <div class="card-header" style="background-color: #f8fafc; font-weight: 700; padding: 15px; border-bottom: 1px solid #e2e8f0;">
                    Documents & Other Details
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Holding Tax Number:</strong></div>
                        <div class="col-md-3">{{ $building->holding_tax_number ?? 'N/A' }}</div>
                        <div class="col-md-3"><strong>Clearance Up To:</strong></div>
                        <div class="col-md-3">{{ $building->holding_tax_clearance_up_to ? $building->holding_tax_clearance_up_to->format('d M, Y') : 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Khajna Clearance Up To:</strong></div>
                        <div class="col-md-9">{{ $building->khajna_clearance_up_to ? $building->khajna_clearance_up_to->format('d M, Y') : 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12"><strong>Alert Notes:</strong></div>
                        <div class="col-md-12 mt-2">{{ $building->alert_notes ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-3 mb-2">
                            @if($building->holding_tax_document)
                                <a href="{{ asset('storage/' . $building->holding_tax_document) }}" target="_blank" class="btn btn-sm btn-outline-primary">View Holding Tax Doc</a>
                            @endif
                        </div>
                        <div class="col-md-3 mb-2">
                            @if($building->khajna_document)
                                <a href="{{ asset('storage/' . $building->khajna_document) }}" target="_blank" class="btn btn-sm btn-outline-primary">View Khajna Doc</a>
                            @endif
                        </div>
                        <div class="col-md-3 mb-2">
                            @if($building->dolil_document)
                                <a href="{{ asset('storage/' . $building->dolil_document) }}" target="_blank" class="btn btn-sm btn-outline-primary">View Dolil</a>
                            @endif
                        </div>
                        <div class="col-md-3 mb-2">
                            @if($building->noksha_document)
                                <a href="{{ asset('storage/' . $building->noksha_document) }}" target="_blank" class="btn btn-sm btn-outline-primary">View Noksha</a>
                            @endif
                        </div>
                        <div class="col-md-3 mb-2">
                            @if($building->mutation_document)
                                <a href="{{ asset('storage/' . $building->mutation_document) }}" target="_blank" class="btn btn-sm btn-outline-primary">View Mutation</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                <div class="card-header" style="background-color: #f8fafc; font-weight: 700; padding: 15px; border-bottom: 1px solid #e2e8f0;">
                    Security Information
                </div>
                <div class="card-body" style="padding: 20px;">
                    @forelse($building->securities as $index => $sec)
                        <div class="mb-4" style="{{ !$loop->last ? 'border-bottom: 1px dashed #e2e8f0; padding-bottom: 15px;' : '' }}">
                            <h5>Security Member {{ $index + 1 }}</h5>
                            <div class="row mt-3">
                                <div class="col-md-3"><strong>Name:</strong></div>
                                <div class="col-md-3">{{ $sec->name }}</div>
                                <div class="col-md-3"><strong>Contact:</strong></div>
                                <div class="col-md-3">{{ $sec->contact }}</div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3"><strong>Father's Name:</strong></div>
                                <div class="col-md-3">{{ $sec->father_name ?? 'N/A' }}</div>
                                <div class="col-md-3"><strong>Mother's Name:</strong></div>
                                <div class="col-md-3">{{ $sec->mother_name ?? 'N/A' }}</div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3"><strong>NID Number:</strong></div>
                                <div class="col-md-3">{{ $sec->nid_number ?? 'N/A' }}</div>
                                <div class="col-md-3"><strong>Birth Certificate:</strong></div>
                                <div class="col-md-3">{{ $sec->birth_certificate_number ?? 'N/A' }}</div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3"><strong>Image:</strong></div>
                                <div class="col-md-9">
                                    @if($sec->image)
                                        <img src="{{ asset('storage/' . $sec->image) }}" width="100" style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 2px; border-radius: 5px;" alt="Image">
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    @if($sec->nid_document)
                                        <a href="{{ asset('storage/' . $sec->nid_document) }}" target="_blank" class="btn btn-sm btn-outline-info">View NID Doc</a>
                                    @endif
                                    @if($sec->birth_certificate_document)
                                        <a href="{{ asset('storage/' . $sec->birth_certificate_document) }}" target="_blank" class="btn btn-sm btn-outline-info">View Birth Cert Doc</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No security information added.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
