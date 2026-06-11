@extends('admin.layouts.app')

@section('content')
<style>
    .info-label { font-weight: 600; color: #64748b; font-size: 0.875rem; margin-bottom: 0.25rem; }
    .info-value { font-weight: 500; color: #0f172a; font-size: 1rem; }
    .card-title { font-weight: 600; color: #1e293b; margin-bottom: 1.5rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.75rem; }
    .bg-style { background-color: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 1.5rem; }
    .total-rent { font-size: 1.25rem; }
</style>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Flat Details: {{ $flat->flat_name }}</h2>
                        <p style="color:white !important;" class="mb-0">
                            Building: <strong>{{ $building->name }}</strong>
                        </p>
                    </div>
                </div>
                <div class="breadcrumb__content__right d-flex gap-2">
                    <a href="{{ route('admin.rent.overview')}}" class="btn btn-secondary">
                        Rent Overview
                    </a>
                    <a href="{{ route('admin.flats.index', $building->id) }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back to Flats
                    </a>
                    <a href="{{ route('admin.flats.edit', [$building->id, $flat->id]) }}" class="btn btn-blue">
                        <i class="fa-solid fa-pen-to-square"></i> Edit Flat
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Basic Info Column -->
        <div class="col-md-8">
            <div class="bg-style">
                <h5 class="card-title"><i class="fa-solid fa-circle-info me-2 text-primary"></i> Basic Information</h5>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="info-label">Flat Name</div>
                        <div class="info-value">{{ $flat->flat_name }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Floor</div>
                        <div class="info-value">{{ $flat->floor ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Intercom Number</div>
                        <div class="info-value">{{ $flat->intercom_number ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Flat Size</div>
                        <div class="info-value">{{ $flat->flat_size ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            @if($flat->status === 'occupied')
                                <span class="badge bg-success">Occupied</span>
                            @elseif($flat->status === 'booked_by_owner')
                                <span class="badge bg-primary">Booked by Owner</span>
                            @else
                                <span class="badge bg-warning text-dark">Vacant</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Available For</div>
                        <div class="info-value">{{ $flat->available_for ? ucfirst($flat->available_for) : 'N/A' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Bill Status</div>
                        <div class="info-value">
                            <span class="badge {{ $flat->bill_status === 'active' ? 'bg-info' : 'bg-secondary' }}">
                                {{ ucfirst($flat->bill_status) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="info-label">Flat Details</div>
                        <div class="info-value">{{ $flat->flat_details ?? 'No additional details provided.' }}</div>
                    </div>
                </div>
            </div>

            <!-- Rent Breakdown -->
            <div class="bg-style">
                <h5 class="card-title"><i class="fa-solid fa-file-invoice-dollar me-2 text-success"></i> Rent & Bills Breakdown</h5>
                <div class="row g-4">
                    @php
                        $rentFields = [
                            'house_rent'         => 'House Rent',
                            'wasa'               => 'WASA',
                            'common_electricity' => 'Common Electricity',
                            'gas'                => 'Gas',
                            'utility'            => 'Utility',
                            'parking'            => 'Parking',
                            'society_bill'       => 'Society Bill',
                            'security'           => 'Security',
                            'other'              => 'Other',
                        ];
                    @endphp
                    @foreach($rentFields as $field => $label)
                        <div class="col-md-4">
                            <div class="info-label">{{ $label }}</div>
                            <div class="info-value">৳ {{ number_format($flat->$field ?? 0, 2) }}</div>
                        </div>
                    @endforeach
                </div>
                <hr class="mt-4 mb-3">
                <div class="row">
                    <div class="col-md-12 text-end">
                        <h4 class="mb-0 text-primary total-rent">Total Rent: <span class="fw-bold">৳ {{ number_format($flat->total_rent, 2) }}</span></h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image & Actions Column -->
        <div class="col-md-4">
            <div class="bg-style">
                <h5 class="card-title"><i class="fa-solid fa-folder-open me-2 text-info"></i> Documents & Images</h5>
                @if(is_array($flat->documents) && count($flat->documents) > 0)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($flat->documents as $doc)
                            <div class="border rounded p-2 text-center bg-light position-relative" style="width: 100px;">
                                <a href="{{ asset($doc) }}" target="_blank" class="text-decoration-none">
                                    @if(preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $doc))
                                        <img src="{{ asset($doc) }}" class="img-thumbnail mb-1" style="width: 100%; height: 70px; object-fit: cover;">
                                    @else
                                        <i class="fa-solid fa-file-lines fa-3x text-secondary mt-2 mb-2 d-block"></i>
                                    @endif
                                    <small style="font-size: 11px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: block; color: #475569;" title="{{ basename($doc) }}">
                                        {{ basename($doc) }}
                                    </small>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-5 border rounded bg-light text-muted">
                        <i class="fa-solid fa-folder-open fa-3x mb-3"></i>
                        <p class="mb-0">No documents available</p>
                    </div>
                @endif
            </div>

            <div class="bg-style">
                <h5 class="card-title"><i class="fa-solid fa-gear me-2 text-secondary"></i> Quick Actions</h5>
                <div class="d-grid gap-2">
                    <form action="{{ route('admin.flats.destroy', [$building->id, $flat->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this flat? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 text-start">
                            <i class="fa-solid fa-trash me-2"></i> Delete Flat
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
