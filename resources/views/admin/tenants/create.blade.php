@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Create New Tenant</h2>
                        <p style="color:white !important;" class="mb-0">Building: {{ $building->name }} | Flat: {{ $flat->flat_name }}</p>
                    </div>
                </div>
                <div class="breadcrumb__content__right d-flex gap-2">
                    <a href="{{ route('admin.tenants.enroll', [$building->id, $flat->id]) }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back to Search
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
                    <form action="{{ route('admin.tenants.store', [$building->id, $flat->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <h4 class="mb-3 text-white border-bottom pb-2">1. Personal Information</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone *</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Profile Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">NID Number</label>
                                <input type="text" name="nid_number" class="form-control" value="{{ old('nid_number') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">NID Document</label>
                                <input type="file" name="nid_document" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" value="{{ old('dob') }}">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Marital Status</label>
                                <select name="marital_status" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Blood Group</label>
                                <input type="text" name="blood_group" class="form-control" value="{{ old('blood_group') }}" placeholder="e.g. O+">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Occupation</label>
                                <input type="text" name="occupation" class="form-control" value="{{ old('occupation') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Occupation Document</label>
                                <input type="file" name="occupation_document" class="form-control">
                            </div>
                        </div>

                        <h4 class="mb-3 text-white border-bottom pb-2 mt-4">2. Addresses & Contact</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Present Address</label>
                                <textarea name="present_address" class="form-control" rows="3">{{ old('present_address') }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Permanent Address</label>
                                <textarea name="permanent_address" class="form-control" rows="3">{{ old('permanent_address') }}</textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Emergency Contact Name</label>
                                <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Emergency Contact Phone</label>
                                <input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Relation</label>
                                <input type="text" name="emergency_contact_relation" class="form-control" value="{{ old('emergency_contact_relation') }}">
                            </div>
                        </div>

                        <h4 class="mb-3 text-white border-bottom pb-2 mt-4">3. Flat Assignment Details</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Date *</label>
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Advance Amount</label>
                                <input type="number" step="0.01" name="advance_amount" class="form-control" value="{{ old('advance_amount') }}" placeholder="0.00">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Advance Document</label>
                                <input type="file" name="advance_document" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Agreement Document</label>
                                <input type="file" name="agreement_document" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Police Form Document</label>
                                <input type="file" name="police_form_document" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Notice Document</label>
                                <input type="file" name="notice_document" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">House Rent Copy</label>
                                <input type="file" name="house_rent_copy" class="form-control">
                            </div>
                        </div>
                        
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-success"><i class="fa-solid fa-save"></i> Save & Enroll</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
