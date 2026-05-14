@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h4 class="fw-bold mb-1">
                        Welcome,
                        {{ auth()->user()->name }}
                    </h4>
                    <p class="text-muted mb-0">
                        Role :
                        <span class="text-capitalize fw-semibold">
                            {{ str_replace('_', ' ', auth()->user()->role) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Total Admins</p>
                    <h4 class="fw-bold mb-0">
                        {{ $totalAdmins }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Pending Approvals</p>
                    <h4 class="fw-bold text-warning mb-0">
                        {{ $pendingCount }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Approved Admins</p>
                    <h4 class="fw-bold text-success mb-0">
                        {{ $approvedAdmins }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Rejected Admins</p>
                    <h4 class="fw-bold text-danger mb-0">
                        {{ $rejectedAdmins }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Pending Admin Approvals Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <span class="text-break">Pending Admin Registrations</span>
                    <a href="{{ route('super_admin.admins.list') }}" class="btn btn-sm btn-primary text-nowrap">
                        View All Admins
                    </a>
                </div>

                <div class="card-body p-0 p-md-3">
                    @if($pendingAdmins->count() > 0)
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>SL</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingAdmins as $key => $admin)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <img src="{{ $admin->image ? asset($admin->image) : asset('default.png') }}"
                                            width="40" height="40" class="rounded-circle object-fit-cover" alt="">
                                    </td>
                                    <td class="text-wrap">{{ $admin->name }}</td>
                                    <td>{{ $admin->phone }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            {{-- Approve --}}
                                            <form action="{{ route('super_admin.change.status', $admin->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-success btn-sm text-nowrap">Approve</button>
                                            </form>
                                            {{-- Reject --}}
                                            <form action="{{ route('super_admin.change.status', $admin->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-danger btn-sm text-nowrap">Reject</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="p-3">
                        <p class="text-muted mb-0">No pending approvals.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection