@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Tenants - {{ $flat->flat_name }}</h2>
                        <p style="color:white !important;" class="mb-0">
                            Building: <strong>{{ $building->name }}</strong> &nbsp;|&nbsp; Flat Status: 
                            <span class="badge {{ $flat->status === 'occupied' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ ucfirst($flat->status) }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="breadcrumb__content__right d-flex gap-2">
                    <a href="{{ route('admin.flats.index', $building->id) }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back to Flats
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Active Tenant Section --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-style">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Current Tenant</h5>
                    @if($flat->status === 'vacant' || !$activeTenant)
                        <a href="{{ route('admin.tenants.enroll', [$building->id, $flat->id]) }}" class="btn btn-blue">
                            <i class="fa-solid fa-user-plus"></i> Enroll Tenant
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($activeTenant && $activeTenant->tenant)
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                @if($activeTenant->tenant->image)
                                    <img src="{{ asset('storage/'.$activeTenant->tenant->image) }}" class="img-fluid rounded-circle" alt="Tenant Image" style="width: 120px; height: 120px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('admin/assets/images/default-avatar.png') }}" class="img-fluid rounded-circle" alt="Default Avatar" style="width: 120px; height: 120px; object-fit: cover;">
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h4>{{ $activeTenant->tenant->name }}</h4>
                                <p class="mb-1"><i class="fa-solid fa-phone text-muted"></i> {{ $activeTenant->tenant->phone }}</p>
                                <p class="mb-1"><i class="fa-solid fa-calendar-alt text-muted"></i> Since: {{ $activeTenant->start_date ? $activeTenant->start_date->format('d M, Y') : 'N/A' }}</p>
                                <p class="mb-0"><i class="fa-solid fa-money-bill text-muted"></i> Advance: ৳ {{ number_format($activeTenant->advance_amount, 2) }}</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('admin.tenants.show', [$building->id, $flat->id, $activeTenant->tenant_id]) }}" class="btn btn-info btn-sm text-white mb-2" style="width:120px;">
                                    <i class="fa-solid fa-eye"></i> View Profile
                                </a><br>
                                <a href="{{ route('admin.tenants.edit', [$building->id, $flat->id, $activeTenant->tenant_id]) }}" class="btn btn-primary btn-sm mb-2" style="width:120px;">
                                    <i class="fa-solid fa-edit"></i> Edit Details
                                </a><br>
                                <form action="{{ route('admin.tenants.vacate', [$building->id, $flat->id, $activeTenant->tenant_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to vacate this tenant?');">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm" style="width:120px;">
                                        <i class="fa-solid fa-sign-out-alt"></i> Vacate Flat
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            @if($flat->status === 'occupied')
                                <h5 class="text-warning">This flat is marked as occupied, but no active tenant is assigned!</h5>
                                <p class="text-muted mb-0">Please enroll a tenant or change the flat status to vacant.</p>
                            @else
                                <h5 class="text-muted">This flat is currently vacant.</h5>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- History Section --}}
    <div class="row">
        <div class="col-md-12">
            <div class="customers__area bg-style mb-30">
                <div class="card-header border-bottom mb-3">
                    <h5 class="mb-0">Tenant History</h5>
                </div>
                <div class="customers__table table-responsive">
                    <table class="row-border table-style table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Tenant Name</th>
                                <th>Phone</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($history as $key => $h)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $h->tenant->name ?? 'N/A' }}</td>
                                    <td>{{ $h->tenant->phone ?? 'N/A' }}</td>
                                    <td>{{ $h->start_date ? $h->start_date->format('d M, Y') : 'N/A' }}</td>
                                    <td>{{ $h->end_date ? $h->end_date->format('d M, Y') : 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $h->status === 'inactive' ? 'Vacated' : ucfirst($h->status) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.tenants.show', [$building->id, $flat->id, $h->tenant_id]) }}" title="View Profile" style="color:#2563eb;" class="me-2">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.tenants.edit', [$building->id, $flat->id, $h->tenant_id]) }}" title="Edit Details" style="color:#10b981;">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No previous tenants found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
