@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Edit Tenant - {{ $tenant->name }}</h2>
                        <p style="color:white !important;" class="mb-0">Building: {{ $building->name }} | Flat: {{ $flat->flat_name }}</p>
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

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card bg-style">
                <div class="card-body p-4">
                    <form action="{{ route('admin.tenants.update', [$building->id, $flat->id, $tenant->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <h4 class="mb-3 text-white border-bottom pb-2">1. Personal Information</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $tenant->name) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone *</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $tenant->phone) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $tenant->email) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Profile Image</label>
                                @if($tenant->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/'.$tenant->image) }}" width="60" class="rounded">
                                    </div>
                                @endif
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">NID Number</label>
                                <input type="text" name="nid_number" class="form-control" value="{{ old('nid_number', $tenant->nid_number) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">NID Document</label>
                                @if($tenant->nid_document)
                                    <a href="{{ asset('storage/'.$tenant->nid_document) }}" target="_blank" class="d-block mb-1"><i class="fa-solid fa-file"></i> View Current</a>
                                @endif
                                <input type="file" name="nid_document" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" value="{{ old('dob', $tenant->dob) }}">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $tenant->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $tenant->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $tenant->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Marital Status</label>
                                <select name="marital_status" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="single" {{ old('marital_status', $tenant->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ old('marital_status', $tenant->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="divorced" {{ old('marital_status', $tenant->marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="widowed" {{ old('marital_status', $tenant->marital_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Blood Group</label>
                                <input type="text" name="blood_group" class="form-control" value="{{ old('blood_group', $tenant->blood_group) }}">
                            </div>
                        </div>

                        <h4 class="mb-3 text-white border-bottom pb-2 mt-4">2. Assignment Docs</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Advance Amount</label>
                                <input type="number" step="0.01" name="advance_amount" class="form-control" value="{{ old('advance_amount', $flatTenant->advance_amount) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Advance Document</label>
                                @if($flatTenant->advance_document)
                                    <a href="{{ asset('storage/'.$flatTenant->advance_document) }}" target="_blank" class="d-block mb-1"><i class="fa-solid fa-file"></i> View Current</a>
                                @endif
                                <input type="file" name="advance_document" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Agreement Document</label>
                                @if($flatTenant->agreement_document)
                                    <a href="{{ asset('storage/'.$flatTenant->agreement_document) }}" target="_blank" class="d-block mb-1"><i class="fa-solid fa-file"></i> View Current</a>
                                @endif
                                <input type="file" name="agreement_document" class="form-control">
                            </div>
                        </div>
                        
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-success"><i class="fa-solid fa-save"></i> Update Details</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
