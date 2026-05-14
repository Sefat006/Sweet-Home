@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Flat List</h2>
                        <p style="color:white !important;" class="mb-0">
                            Building: <strong>{{ $building->name }}</strong> &nbsp;|&nbsp; Address: {{ $building->address }}
                        </p>
                    </div>
                </div>
                <div class="breadcrumb__content__right d-flex gap-2">
                    <a href="{{ route('admin.building.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Buildings
                    </a>
                    <a href="{{ route('admin.flats.create', $building->id) }}" class="btn btn-blue">
                        <i class="fa-solid fa-plus"></i> Add New Flat
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-3">
        @php
            $total    = $flats->count();
            $occupied = $flats->where('status','occupied')->count();
            $vacant   = $flats->where('status','vacant')->count();
            $active   = $flats->where('bill_status','active')->count();
        @endphp
        <div class="col-md-3">
            <div class="card text-center p-3 bg-style">
                <h5 class="mb-1">{{ $total }}</h5><small class="text-muted">Total Flats</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 bg-style">
                <h5 class="mb-1 text-success">{{ $occupied }}</h5><small class="text-muted">Occupied</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 bg-style">
                <h5 class="mb-1 text-warning">{{ $vacant }}</h5><small class="text-muted">Vacant</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 bg-style">
                <h5 class="mb-1 text-info">{{ $active }}</h5><small class="text-muted">Bill Active</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="customers__area bg-style mb-30">
                <div class="customers__table table-responsive">
                    <table class="row-border table-style table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Flat</th>
                                <th>Floor</th>
                                <th>Intercom</th>
                                <th>Status</th>
                                <th>Available For</th>
                                <th>Total Rent</th>
                                <th>Bill Status</th>
                                <th width="220">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($flats as $key => $flat)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <strong>{{ $flat->flat_name }}</strong>
                                    @if($flat->image)
                                        <br><img src="{{ asset('storage/'.$flat->image) }}" width="40" height="40" class="object-fit-cover rounded mt-1">
                                    @endif
                                </td>
                                <td>{{ $flat->floor ?? '—' }}</td>
                                <td>{{ $flat->intercom_number ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $flat->status === 'occupied' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ ucfirst($flat->status) }}
                                    </span>
                                </td>
                                <td>{{ $flat->available_for ? ucfirst($flat->available_for) : '—' }}</td>
                                <td>৳ {{ number_format($flat->total_rent, 0) }}</td>
                                <td>
                                    <span class="badge {{ $flat->bill_status === 'active' ? 'bg-info' : 'bg-secondary' }}">
                                        {{ ucfirst($flat->bill_status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 align-items-center flex-wrap">
                                        {{-- View --}}
                                        <a href="{{ route('admin.flats.show', [$building->id, $flat->id]) }}"
                                           title="View Flat" style="color:#2563eb;">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        {{-- Edit --}}
                                        <a href="{{ route('admin.flats.edit', [$building->id, $flat->id]) }}"
                                           title="Edit Flat" style="color:#16a34a;">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        {{-- Tenant --}}
                                        <a href="{{ route('admin.tenants.index', [$building->id, $flat->id]) }}" title="Tenants" style="color:#7c3aed;">
                                            <i class="fa-solid fa-users"></i>
                                        </a>
                                        {{-- Monthly Bill --}}
                                        <a href="{{ route('admin.bills.index', [$building->id, $flat->id]) }}" title="Bills" style="color:#d97706;">
                                            <i class="fa-solid fa-file-invoice-dollar"></i>
                                        </a>
                                        {{-- Delete --}}
                                        <form action="{{ route('admin.flats.destroy', [$building->id, $flat->id]) }}"
                                              method="POST" style="display:inline-block;"
                                              onsubmit="return confirm('Delete this flat?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" style="border:none;background:none;color:red;" title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">No flats found for this building.</td>
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