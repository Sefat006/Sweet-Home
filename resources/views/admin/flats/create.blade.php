@extends('admin.layouts.app')

@section('content')
<style>
    .form-label {
        color: #000 !important;
    }
    .drag-drop-box {
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        padding: 20px 10px;
        text-align: center;
        cursor: pointer;
        background-color: #f8fafc;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .drag-drop-box:hover {
        background-color: #f1f5f9;
        border-color: #94a3b8;
    }
    .drag-drop-box input[type="file"] {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }
    .drag-drop-box .icon {
        font-size: 24px;
        color: #64748b;
        margin-bottom: 8px;
    }
    .drag-drop-text {
        font-size: 13px;
        color: #64748b;
        margin: 0;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Add New Flat</h2>
                        <p style="color:white !important;" class="mb-0">
                            Building: <strong>{{ $building->name }}</strong>
                        </p>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <a href="{{ route('admin.flats.index', $building->id) }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back to Flats
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.flats.store', $building->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- ── BASIC INFO ── --}}
        <div class="row">
            <div class="col-md-12">
                <div class="bg-style p-4 mb-4">
                    <h5 class="mb-3 fw-semibold">Basic Information</h5>
                    <div class="row g-3">

                        <div class="col-md-3">
                            <label class="form-label">Flat Name <span class="text-danger">*</span></label>
                            <input type="text" name="flat_name" class="form-control @error('flat_name') is-invalid @enderror"
                                   value="{{ old('flat_name') }}" placeholder="e.g. 2A" required>
                            @error('flat_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Floor</label>
                            <input type="text" name="floor" class="form-control @error('floor') is-invalid @enderror"
                                   value="{{ old('floor') }}" placeholder="e.g. 2nd">
                            @error('floor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Intercom Number</label>
                            <input type="text" name="intercom_number" class="form-control @error('intercom_number') is-invalid @enderror"
                                   value="{{ old('intercom_number') }}" placeholder="e.g. 201">
                            @error('intercom_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Flat Size</label>
                            <input type="text" name="flat_size" class="form-control @error('flat_size') is-invalid @enderror"
                                   value="{{ old('flat_size') }}" placeholder="e.g. 1200 sqft">
                            @error('flat_size')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="vacant"   {{ old('status') === 'vacant'   ? 'selected' : '' }}>Vacant</option>
                                <option value="occupied" {{ old('status') === 'occupied' ? 'selected' : '' }}>Occupied</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Available For</label>
                            <input list="available_for_options" name="available_for" class="form-control @error('available_for') is-invalid @enderror"
                                   value="{{ old('available_for') }}" placeholder="e.g. rent, sale">
                            <datalist id="available_for_options">
                                <option value="Rent">
                                <option value="Sale">
                                <option value="Lease">
                            </datalist>
                            @error('available_for')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Bill Status <span class="text-danger">*</span></label>
                            <select name="bill_status" class="form-select @error('bill_status') is-invalid @enderror" required>
                                <option value="inactive" {{ old('bill_status','inactive') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="active"   {{ old('bill_status') === 'active'             ? 'selected' : '' }}>Active</option>
                            </select>
                            @error('bill_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Flat Image</label>
                            <div class="drag-drop-box">
                                <i class="fa-solid fa-cloud-arrow-up icon"></i>
                                <p class="drag-drop-text">Click or Drag & Drop</p>
                                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                                       accept="image/jpeg,image/png,image/jpg" onchange="previewFileName(this)">
                            </div>
                            <small id="file-name" class="text-muted d-block mt-1"></small>
                            @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Flat Details</label>
                            <textarea name="flat_details" class="form-control @error('flat_details') is-invalid @enderror"
                                      rows="3" placeholder="Any additional details about the flat...">{{ old('flat_details') }}</textarea>
                            @error('flat_details')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── RENT BREAKDOWN ── --}}
        <div class="row">
            <div class="col-md-12">
                <div class="bg-style p-4 mb-4">
                    <h5 class="mb-3 fw-semibold">Rent Breakdown <small class="text-muted fs-6">(leave 0 if not applicable)</small></h5>
                    <div class="row g-3">
                        @php
                        $rentFields = [
                            'house_rent'         => 'House Rent',
                            'wasa'               => 'WASA',
                            'common_electricity' => 'Common Electricity',
                            'gas'                => 'Gas',
                            'utility'            => 'Utility',
                            'parking'            => 'Parking',
                            'society_bill'       => 'Society Bill',
                            'security'           => 'Security',
                            'other'              => 'Other',
                        ];
                        @endphp

                        @foreach($rentFields as $field => $label)
                        <div class="col-md-4">
                            <label class="form-label">{{ $label }} (৳)</label>
                            <input type="number" name="{{ $field }}" step="0.01" min="0"
                                   class="form-control rent-field @error($field) is-invalid @enderror"
                                   value="{{ old($field, 0) }}" placeholder="0">
                            @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @endforeach

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Total Rent (৳)</label>
                            <input type="text" id="total_rent_display" class="form-control bg-light fw-bold"
                                   value="0" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 d-flex gap-2 justify-content-end mb-4">
                <a href="{{ route('admin.flats.index', $building->id) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-blue">Save Flat</button>
            </div>
        </div>
    </form>
</div>

<script>
function previewFileName(input) {
    if (input.files && input.files[0]) {
        document.getElementById('file-name').innerText = 'Selected: ' + input.files[0].name;
    } else {
        document.getElementById('file-name').innerText = '';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const fields = document.querySelectorAll('.rent-field');
    const total  = document.getElementById('total_rent_display');
    function calc() {
        let sum = 0;
        fields.forEach(f => sum += parseFloat(f.value) || 0);
        total.value = sum.toLocaleString('en-BD', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
    fields.forEach(f => f.addEventListener('input', calc));
    calc();
});
</script>
@endsection