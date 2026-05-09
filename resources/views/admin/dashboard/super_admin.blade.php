@extends('admin.layouts.app')

@section('content')

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

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1 small">Total Admins</p>
                <h4 class="fw-bold mb-0">
                    {{ $totalAdmins }}
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1 small">Pending Approvals</p>
                <h4 class="fw-bold text-warning mb-0">
                    {{ $pendingCount }}
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1 small">Approved Admins</p>
                <h4 class="fw-bold text-success mb-0">
                    {{ $approvedAdmins }}
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
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

            <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                <span>Pending Admin Registrations</span>

                <a href="{{ route('super_admin.admins.list') }}"
                   class="btn btn-sm btn-primary">
                    View All Admins
                </a>
            </div>

            <div class="card-body">

                @if($pendingAdmins->count() > 0)

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th width="220">Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($pendingAdmins as $key => $admin)

                                    <tr>

                                        <td>
                                            {{ $key + 1 }}
                                        </td>

                                        <td>
                                            <img
                                                src="{{ $admin->image ? asset($admin->image) : asset('default.png') }}"
                                                width="50"
                                                height="50"
                                                class="rounded-circle object-fit-cover"
                                                alt=""
                                            >
                                        </td>

                                        <td>
                                            {{ $admin->name }}
                                        </td>

                                        <td>
                                            {{ $admin->phone }}
                                        </td>

                                        <td>
                                            {{ $admin->email }}
                                        </td>

                                        <td>
                                            <span class="badge bg-warning">
                                                Pending
                                            </span>
                                        </td>

                                        <td>

                                            <div class="d-flex gap-2">

                                                {{-- Approve --}}
                                                <form action="{{ route('super_admin.change.status', $admin->id) }}"
                                                      method="POST">
                                                    @csrf

                                                    <input type="hidden"
                                                           name="status"
                                                           value="approved">

                                                    <button type="submit"
                                                            class="btn btn-success btn-sm">
                                                        Approve
                                                    </button>
                                                </form>

                                                {{-- Reject --}}
                                                <form action="{{ route('super_admin.change.status', $admin->id) }}"
                                                      method="POST">
                                                    @csrf

                                                    <input type="hidden"
                                                           name="status"
                                                           value="rejected">

                                                    <button type="submit"
                                                            class="btn btn-danger btn-sm">
                                                        Reject
                                                    </button>
                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <p class="text-muted mb-0">
                        No pending approvals.
                    </p>

                @endif

            </div>

        </div>

    </div>
</div>

@endsection