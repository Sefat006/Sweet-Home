@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="row">

        <div class="col-md-12">

            <div class="breadcrumb__content">

                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>Admin List</h2>

                        <p class="mb-0 text-muted">
                            Logged in as :
                            <strong>
                                {{ auth()->user()->name }}
                            </strong>
                            (
                            {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                            )
                        </p>
                    </div>
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
                                <th>Image</th>
                                <th>Admin ID</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th width="250">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($admins as $key => $admin)

                            <tr>

                                <td>
                                    {{ $key + 1 }}
                                </td>

                                <td>
                                    <img
                                        src="{{ $admin->image ? asset($admin->image) : asset('default.png') }}"
                                        width="50"
                                        height="50"
                                        class="object-fit-cover"
                                        alt="">
                                </td>

                                <td>
                                    {{ $admin->admin_id }}
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

                                {{-- Status --}}
                                <td>

                                    <form action="{{ route('super_admin.change.status', $admin->id) }}"
                                        method="POST">

                                        @csrf

                                        <select name="status"
                                            onchange="this.form.submit()"
                                            class="form-select form-select-sm

                    @if($admin->status == 'approved')
                        border-success text-success
                    @elseif($admin->status == 'pending')
                        border-warning text-warning
                    @else
                        border-danger text-danger
                    @endif
                ">

                                            <option value="approved"
                                                {{ $admin->status == 'approved' ? 'selected' : '' }}>
                                                Approved
                                            </option>

                                            <option value="pending"
                                                {{ $admin->status == 'pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>

                                            <option value="rejected"
                                                {{ $admin->status == 'rejected' ? 'selected' : '' }}>
                                                Rejected
                                            </option>

                                        </select>

                                    </form>

                                </td>

                                {{-- Action --}}
                                <td>

                                    <div class="d-flex gap-2 align-items-center">

                                        {{-- View --}}
                                        <a href="{{ route('super_admin.admin.show', $admin->id) }}"
                                            class=""
                                            title="View">

                                            <i class="fa-solid fa-eye"></i>

                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('super_admin.admin.delete', $admin->id) }}"
                                            method="POST"
                                            style="display:inline-block;"
                                            onsubmit="return confirm('Are you sure you want to delete this super admin?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                style="border:none;background:none;color:red;">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>

                                        </form>

                                    </div>

                                </td>

                                </td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="8" class="text-center">
                                    No admins found.
                                </td>
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