@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Edit Flat: {{ $flat->flat_name }}</h2>
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

    <form action="{{ route('admin.flats.update', [$building->id, $flat->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ── BASIC INFO ── --}}
        <div class="row">
            <div class="col-md-12">
                <div class="bg-style p-4 mb-4">
                    <h5 class="mb-3 fw-semibold">Basic Information</h5>
                    <div class="row g-3">

                        <div class="col-md-3">
                            <label class="form-label">Flat Name <span class="text-danger">*</span></label>
                            <input type="text" name="flat_name" class="form-control @error('flat_name') is-invalid @enderror"
                                value="{{ old('flat_name', $flat->flat_name) }}" placeholder="e.g. 2A" required>
                            @error('flat_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Floor</label>
                            <input type="text" name="floor" class="form-control @error('floor') is-invalid @enderror"
                                value="{{ old('floor', $flat->floor) }}" placeholder="e.g. 2nd">
                            @error('floor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Intercom Number</label>
                            <input type="text" name="intercom_number" class="form-control @error('intercom_number') is-invalid @enderror"
                                value="{{ old('intercom_number', $flat->intercom_number) }}" placeholder="e.g. 201">
                            @error('intercom_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Flat Size</label>
                            <input type="text" name="flat_size" class="form-control @error('flat_size') is-invalid @enderror"
                                value="{{ old('flat_size', $flat->flat_size) }}" placeholder="e.g. 1200 sqft">
                            @error('flat_size')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="vacant"           {{ old('status', $flat->status) === 'vacant'           ? 'selected' : '' }}>Vacant</option>
                                <option value="occupied"         {{ old('status', $flat->status) === 'occupied'         ? 'selected' : '' }}>Occupied</option>
                                <option value="booked_by_owner" {{ old('status', $flat->status) === 'booked_by_owner' ? 'selected' : '' }}>Booked by Owner</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Available For</label>
                            <input list="available_for_options" name="available_for" class="form-control @error('available_for') is-invalid @enderror"
                                value="{{ old('available_for', $flat->available_for) }}" placeholder="e.g. rent, sale">
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
                                <option value="inactive" {{ old('bill_status', $flat->bill_status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="active" {{ old('bill_status', $flat->bill_status) === 'active'   ? 'selected' : '' }}>Active</option>
                            </select>
                            @error('bill_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Documents / Images</label>
                            <input type="file" name="documents[]" multiple class="form-control @error('documents.*') is-invalid @enderror"
                                accept="image/*,.pdf,.doc,.docx" onchange="previewFiles(this)">
                            @error('documents.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            
                            <div class="mt-2">
                                <label class="form-label mb-1 fs-6">Selected New Files:</label>
                                <div id="file-previews" class="d-flex flex-wrap gap-2"></div>
                            </div>

                            @if(is_array($flat->documents) && count($flat->documents) > 0)
                            <div class="mt-3 p-2 border rounded bg-light">
                                <label class="form-label mb-2 fs-6">Existing Files (Saved):</label>
                                <div class="d-flex flex-wrap gap-2" id="existing-files-container">
                                    @foreach($flat->documents as $doc)
                                    <div class="existing-doc-wrapper border rounded p-1 text-center bg-white position-relative" style="width: 80px;">
                                        <input type="hidden" name="existing_documents[]" value="{{ $doc }}">
                                        
                                        @if(preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $doc))
                                            <img src="{{ asset($doc) }}" class="img-thumbnail mb-1" style="width: 100%; height: 60px; object-fit: cover;">
                                        @else
                                            <i class="fa-solid fa-file-lines fa-2x text-secondary mt-2 mb-2 d-block"></i>
                                        @endif
                                        
                                        <small style="font-size: 10px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: block;" title="{{ basename($doc) }}">
                                            {{ basename($doc) }}
                                        </small>

                                        <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: -5px; right: -5px; padding: 0 4px; border-radius: 50%; font-size: 10px;" onclick="removeExistingDoc(this)">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Flat Details</label>
                            <textarea name="flat_details" class="form-control @error('flat_details') is-invalid @enderror"
                                rows="3" placeholder="Any additional details about the flat...">{{ old('flat_details', $flat->flat_details) }}</textarea>
                            @error('flat_details')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── RENT BREAKDOWN ── --}}
        {{-- ── RENT BREAKDOWN ── --}}
        <div class="row">
            <div class="col-md-12">
                <div class="bg-style p-4 mb-4">

                    <h5 class="mb-3 fw-semibold">
                        Rent Breakdown
                        <small class="text-muted fs-6">(leave 0 if not applicable)</small>
                    </h5>

                    @php
                    $rentFields = [
                    'house_rent' => 'House Rent',
                    'wasa' => 'WASA',
                    'common_electricity' => 'Common Electricity',
                    'gas' => 'Gas',
                    'utility' => 'Utility',
                    'parking' => 'Parking',
                    'society_bill' => 'Society Bill',
                    'security' => 'Security',
                    'other' => 'Other',
                    ];

                    $hasBreakdown = ($flat->wasa > 0 || $flat->common_electricity > 0 || $flat->gas > 0 || $flat->utility > 0 || $flat->parking > 0 || $flat->society_bill > 0 || $flat->security > 0 || $flat->other > 0);
                    $initialRentType = '';
                    if ($hasBreakdown) {
                        $initialRentType = 'full_breakdown';
                    } elseif ($flat->house_rent > 0) {
                        $initialRentType = 'house_only';
                    }
                    $selectedRentType = old('rent_type', $initialRentType);
                    @endphp

                    <div class="row g-3">

                        {{-- Rent Type --}}
                        <div class="col-md-4">
                            <label class="form-label">Rent Type</label>
                            <select name="rent_type" id="rent_type" class="form-select">
                                <option value="" {{ $selectedRentType === '' ? 'selected' : '' }}>Select</option>
                                <option value="house_only" {{ $selectedRentType === 'house_only' ? 'selected' : '' }}>
                                    House Rent Only
                                </option>
                                <option value="full_breakdown" {{ $selectedRentType === 'full_breakdown' ? 'selected' : '' }}>
                                    Full Breakdown
                                </option>
                            </select>
                        </div>

                        {{-- House Rent --}}
                        <div class="col-md-4 house-rent-wrapper">
                            <label class="form-label">House Rent (৳)</label>
                            <input type="number"
                                name="house_rent"
                                step="0.01"
                                min="0"
                                class="form-control rent-field @error('house_rent') is-invalid @enderror"
                                value="{{ old('house_rent', $flat->house_rent ?? 0) }}"
                                placeholder="0">

                            @error('house_rent')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Other Breakdown Fields --}}
                        @foreach($rentFields as $field => $label)

                        @if($field !== 'house_rent')

                        <div class="col-md-4 breakdown-field">
                            <label class="form-label">{{ $label }} (৳)</label>

                            <input type="number"
                                name="{{ $field }}"
                                step="0.01"
                                min="0"
                                class="form-control rent-field @error($field) is-invalid @enderror"
                                value="{{ old($field, $flat->$field ?? 0) }}"
                                placeholder="0">

                            @error($field)
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @endif

                        @endforeach

                        {{-- Total Rent --}}
                        <div class="col-md-4 total-rent-wrapper">
                            <label class="form-label fw-semibold">
                                Total Rent (৳)
                            </label>

                            <input type="text"
                                id="total_rent_display"
                                class="form-control bg-light fw-bold"
                                value="0"
                                readonly>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 d-flex gap-2 justify-content-end mb-4">
                <a href="{{ route('admin.flats.index', $building->id) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-blue">Update Flat</button>
            </div>
        </div>
    </form>
</div>

<script>
function previewFiles(input) {
    const container = document.getElementById('file-previews');
    container.innerHTML = '';
    if (input.files) {
        Array.from(input.files).forEach(file => {
            const wrapper = document.createElement('div');
            wrapper.className = 'border rounded p-1 text-center bg-white';
            wrapper.style.width = '80px';
            wrapper.style.overflow = 'hidden';
            wrapper.style.textOverflow = 'ellipsis';
            wrapper.style.whiteSpace = 'nowrap';
            wrapper.title = file.name;

            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'img-thumbnail mb-1';
                img.style.width = '100%';
                img.style.height = '60px';
                img.style.objectFit = 'cover';
                wrapper.appendChild(img);
            } else {
                const icon = document.createElement('i');
                icon.className = 'fa-solid fa-file-lines fa-2x text-secondary mt-2 mb-2 d-block';
                wrapper.appendChild(icon);
            }
            
            const name = document.createElement('small');
            name.style.fontSize = '10px';
            name.innerText = file.name;
            wrapper.appendChild(name);
            
            container.appendChild(wrapper);
        });
    }
}

function removeExistingDoc(btn) {
    if(confirm('Remove this file? It will be deleted permanently when you save.')) {
        btn.closest('.existing-doc-wrapper').remove();
    }
}

document.addEventListener('DOMContentLoaded', function () {

    const rentType = document.getElementById('rent_type');
    const breakdownFields = document.querySelectorAll('.breakdown-field');
    const rentFields = document.querySelectorAll('.rent-field');
    const totalRent = document.getElementById('total_rent_display');
    const houseRentWrapper = document.querySelector('.house-rent-wrapper');
    const totalRentWrapper = document.querySelector('.total-rent-wrapper');

    function calculateTotalRent() {
        let total = 0;

        rentFields.forEach(field => {
            total += parseFloat(field.value) || 0;
        });

        totalRent.value = total.toLocaleString('en-BD', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function toggleRentType() {
        const val = rentType.value;

        if (val === 'house_only') {
            if (houseRentWrapper) houseRentWrapper.style.display = '';

            breakdownFields.forEach(field => {
                field.style.display = 'none';
                const input = field.querySelector('input');
                if (input) {
                    input.value = 0;
                }
            });

            if (totalRentWrapper) totalRentWrapper.style.display = 'none';

        } else if (val === 'full_breakdown') {
            if (houseRentWrapper) houseRentWrapper.style.display = '';

            breakdownFields.forEach(field => {
                field.style.display = '';
            });

            if (totalRentWrapper) totalRentWrapper.style.display = '';

        } else {
            if (houseRentWrapper) {
                houseRentWrapper.style.display = 'none';
                const input = houseRentWrapper.querySelector('input');
                if (input) {
                    input.value = 0;
                }
            }

            breakdownFields.forEach(field => {
                field.style.display = 'none';
                const input = field.querySelector('input');
                if (input) {
                    input.value = 0;
                }
            });

            if (totalRentWrapper) totalRentWrapper.style.display = 'none';
        }

        calculateTotalRent();
    }

    rentType.addEventListener('change', toggleRentType);

    rentFields.forEach(field => {
        field.addEventListener('input', calculateTotalRent);
    });

    toggleRentType();
});
</script>
@endsection