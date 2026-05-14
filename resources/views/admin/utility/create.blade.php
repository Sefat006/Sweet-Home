@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Add Utility Bill</h2>
                        <p style="color:white !important;" class="mb-0">
                            Building: <strong>{{ $building->name }}</strong>
                        </p>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <a href="{{ route('admin.utility.index', $building->id) }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back to Bills
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="form-vertical__item bg-style mb-30">
                <form action="{{ route('admin.utility.store', $building->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <h5 class="mb-4">Bill Information</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Bill Type <span class="text-danger">*</span></label>
                                <select name="bill_type" class="form-select" required>
                                    <option value="">Select Type</option>
                                    <option value="wasa" {{ old('bill_type') == 'wasa' ? 'selected' : '' }}>WASA</option>
                                    <option value="titas_gas" {{ old('bill_type') == 'titas_gas' ? 'selected' : '' }}>TITAS Gas</option>
                                    <option value="holding_tax" {{ old('bill_type') == 'holding_tax' ? 'selected' : '' }}>Holding Tax</option>
                                    <option value="electricity" {{ old('bill_type') == 'electricity' ? 'selected' : '' }}>Electricity</option>
                                    <option value="other" {{ old('bill_type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('bill_type') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Billing Name (e.g. Block A Water)</label>
                                <input type="text" name="billing_name" value="{{ old('billing_name') }}" class="form-control" placeholder="Optional identifier">
                                @error('billing_name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Bill Month (For monthly bills)</label>
                                <input type="month" name="bill_month" value="{{ old('bill_month') }}" class="form-control">
                                @error('bill_month') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Bill Year (For yearly bills)</label>
                                <input type="number" name="bill_year" value="{{ old('bill_year') }}" class="form-control" placeholder="e.g. 2026">
                                @error('bill_year') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Invoice Number</label>
                                <input type="text" name="invoice_number" value="{{ old('invoice_number') }}" class="form-control" placeholder="Optional">
                                @error('invoice_number') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Due Date</label>
                                <input type="date" name="due_date" value="{{ old('due_date') }}" class="form-control">
                                @error('due_date') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Total Amount (৳) <span class="text-danger">*</span></label>
                                <input type="number" name="total_amount" value="{{ old('total_amount') }}" class="form-control" step="0.01" min="0" required>
                                @error('total_amount') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Document Upload (PDF/JPG/PNG)</label>
                                <input type="file" name="document" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                @error('document') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="input__group mb-3">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes">{{ old('notes') }}</textarea>
                        @error('notes') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-blue">Save Utility Bill</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
