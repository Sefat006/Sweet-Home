@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Enroll Tenant - {{ $flat->flat_name }}</h2>
                        <p style="color:white !important;" class="mb-0">Building: {{ $building->name }}</p>
                    </div>
                </div>
                <div class="breadcrumb__content__right d-flex gap-2">
                    <a href="{{ route('admin.tenants.index', [$building->id, $flat->id]) }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card bg-style mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Step 1: Search Existing Tenant or Create New</h5>
                    <a href="{{ route('admin.tenants.create', [$building->id, $flat->id]) }}" class="btn btn-blue">
                        <i class="fa-solid fa-user-plus"></i> Create New Tenant
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tenants.search', [$building->id, $flat->id]) }}" method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="query" class="form-control" placeholder="Search by name, phone, or NID..." value="{{ $query ?? '' }}" required>
                            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-search"></i> Search</button>
                            <a href="{{ route('admin.tenants.enroll', [$building->id, $flat->id]) }}" class="btn btn-secondary">Clear</a>
                        </div>
                    </form>

                    @if(isset($query))
                        <h6 class="mb-3">Search Results for "{{ $query }}"</h6>
                        @if($tenants && $tenants->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered text-white">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>NID</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tenants as $tenant)
                                            <tr>
                                                <td>{{ $tenant->name }}</td>
                                                <td>{{ $tenant->phone }}</td>
                                                <td>{{ $tenant->nid_number ?? 'N/A' }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#assignModal{{ $tenant->id }}">
                                                        Assign to {{ $flat->flat_name }}
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Assign Modal -->
                                            <div class="modal fade" id="assignModal{{ $tenant->id }}" tabindex="-1" aria-labelledby="assignModalLabel{{ $tenant->id }}" aria-hidden="true">
                                              <div class="modal-dialog modal-lg">
                                                <div class="modal-content bg-style">
                                                  <form action="{{ route('admin.tenants.assign', [$building->id, $flat->id]) }}" method="POST" enctype="multipart/form-data">
                                                      @csrf
                                                      <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                                                      <div class="modal-header">
                                                        <h5 class="modal-title" id="assignModalLabel{{ $tenant->id }}">Assign Tenant: {{ $tenant->name }}</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                      </div>
                                                      <div class="modal-body">
                                                          <div class="row">
                                                              <div class="col-md-6 mb-3">
                                                                  <label class="form-label">Start Date *</label>
                                                                  <input type="date" name="start_date" class="form-control" required>
                                                              </div>
                                                              <div class="col-md-6 mb-3">
                                                                  <label class="form-label">Advance Amount</label>
                                                                  <input type="number" step="0.01" name="advance_amount" class="form-control" placeholder="0.00">
                                                              </div>
                                                              <div class="col-md-6 mb-3">
                                                                  <label class="form-label">Advance Document</label>
                                                                  <input type="file" name="advance_document" class="form-control">
                                                              </div>
                                                              <div class="col-md-6 mb-3">
                                                                  <label class="form-label">Agreement Document</label>
                                                                  <input type="file" name="agreement_document" class="form-control">
                                                              </div>
                                                              <div class="col-md-6 mb-3">
                                                                  <label class="form-label">Police Form Document</label>
                                                                  <input type="file" name="police_form_document" class="form-control">
                                                              </div>
                                                              <div class="col-md-6 mb-3">
                                                                  <label class="form-label">Notice Document</label>
                                                                  <input type="file" name="notice_document" class="form-control">
                                                              </div>
                                                              <div class="col-md-6 mb-3">
                                                                  <label class="form-label">House Rent Copy</label>
                                                                  <input type="file" name="house_rent_copy" class="form-control">
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Confirm Assignment</button>
                                                      </div>
                                                  </form>
                                                </div>
                                              </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning">No existing tenants found matching your query.</div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <p class="text-muted">Search for an existing tenant above or create a completely new tenant profile.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
