@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="row">

        <div class="col-md-12">

            <div class="breadcrumb__content">

                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>Super Admin List</h2>

                        <p class="mb-0 text-muted">
                            Logged in as :
                            <strong>{{ auth()->user()->name }}</strong>
                            ({{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }})
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

                    <table class="table table-style">

                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($superAdmins as $key => $admin)

                            <tr>

                                <td>{{ $key + 1 }}</td>

                                <td>
                                    <img src="{{ $admin->image ? asset($admin->image) : asset('default.png') }}"
                                         width="45"
                                         height="45"
                                         class="rounded-circle object-fit-cover">
                                </td>

                                <td>{{ $admin->name }}</td>

                                <td>{{ $admin->email }}</td>

                                <td>{{ $admin->phone }}</td>

                                <td>
                                    <span class="badge bg-success">
                                        Super Admin
                                    </span>
                                </td>

                                <td>

                                    <div class="d-flex gap-2 align-items-center">

                                        {{-- View --}}
                                        <a href="#"
                                           title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('super_admin.delete', $admin->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this super admin?')">

                                            @csrf

                                            <button type="submit"
                                                    style="border:none;background:none;color:red;">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="7" class="text-center">
                                    No super admins found.
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