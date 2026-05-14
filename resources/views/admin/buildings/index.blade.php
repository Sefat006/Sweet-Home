@extends('admin.layouts.app')

@section('content')

<style>
    .container-fluid, .breadcrumb__title h2, .breadcrumb__title p, .customers__table table th, .customers__table table td {
        color: #000;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Building List</h2>
                        <p style="color: white !important;"
                         class="mb-0 text-muted">
                            @if(auth()->user()->role === 'super_admin' && isset($owner))
                                Owner Name: <strong>{{ $owner->name }}</strong><br>
                                Owner ID: <strong>{{ $owner->admin_id }}</strong>
                            @else
                                Logged in as: <strong>{{ auth()->user()->name }}</strong> ({{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }})
                            @endif
                        </p>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <a href="{{ route('admin.building.create') }}" class="btn btn-blue">Add New Building</a>
                </div>
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
                                <th>Logo</th>
                                <th>Building Name</th>
                                <th>No of Floors</th>
                                <th>Address</th>
                                <th width="250">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($buildings as $key => $building)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    @if($building->logo)
                                        <img src="{{ asset('storage/' . $building->logo) }}" width="50" height="50" class="object-fit-cover" alt="">
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $building->name }}</td>
                                <td>{{ $building->no_of_floor }}</td>
                                <td>{{ Str::limit($building->address, 50) }}</td>
                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        <a href="{{ route('admin.building.show', $building->id) }}" title="View" style="color: #2563eb;">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.flats.index', $building->id) }}" title="View Flats" style="color: #7c3aed;">
                                            <i class="fa-solid fa-door-open"></i>
                                        </a>
                                        <a href="{{ route('admin.building.edit', $building->id) }}" title="Edit" style="color: #16a34a;">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('admin.building.destroy', $building->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this building?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="border:none;background:none;color:red;">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No buildings found.</td>
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