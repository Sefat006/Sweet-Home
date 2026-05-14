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
                            <span class="badge {{ $flat->status === 'occupied' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ ucfirst($flat->status) }}
                            </span>
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
                <h5 class="card-title"><i class="fa-solid fa-image me-2 text-info"></i> Flat Image</h5>
                @if($flat->image)
                    <img src="{{ asset('storage/' . $flat->image) }}" class="img-fluid rounded border" alt="{{ $flat->flat_name }}">
                @else
                    <div class="text-center p-5 border rounded bg-light text-muted">
                        <i class="fa-solid fa-house-chimney-window fa-3x mb-3"></i>
                        <p class="mb-0">No image available</p>
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
