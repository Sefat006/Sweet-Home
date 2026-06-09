@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Edit & Pay Utility Bill</h2>
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
                <form action="{{ route('admin.utility.update', [$building->id, $bill->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <h5 class="mb-4 border-bottom pb-2">Bill Information</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Bill Type <span class="text-danger">*</span></label>
                                <select name="bill_type" class="form-select" required>
                                    <option value="wasa" {{ old('bill_type', $bill->bill_type) == 'wasa' ? 'selected' : '' }}>WASA</option>
                                    <option value="titas_gas" {{ old('bill_type', $bill->bill_type) == 'titas_gas' ? 'selected' : '' }}>TITAS Gas</option>
                                    <option value="holding_tax" {{ old('bill_type', $bill->bill_type) == 'holding_tax' ? 'selected' : '' }}>Holding Tax</option>
                                    <option value="electricity" {{ old('bill_type', $bill->bill_type) == 'electricity' ? 'selected' : '' }}>Electricity</option>
                                    <option value="other" {{ old('bill_type', $bill->bill_type) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('bill_type') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Billing Name</label>
                                <input type="text" name="billing_name" value="{{ old('billing_name', $bill->billing_name) }}" class="form-control">
                                @error('billing_name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Bill Month</label>
                                <input type="month" name="bill_month" value="{{ old('bill_month', $bill->bill_month) }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Bill Year</label>
                                <input type="number" name="bill_year" value="{{ old('bill_year', $bill->bill_year) }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Invoice Number</label>
                                <input type="text" name="invoice_number" value="{{ old('invoice_number', $bill->invoice_number) }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Due Date</label>
                                <input type="date" name="due_date" value="{{ old('due_date', $bill->due_date ? $bill->due_date->format('Y-m-d') : '') }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Total Amount (৳) <span class="text-danger">*</span></label>
                                <input type="number" name="total_amount" value="{{ old('total_amount', $bill->total_amount) }}" class="form-control" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Document Upload (PDF/JPG/PNG)</label>
                                <input type="file" name="document" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                @if($bill->document)
                                    <small class="text-muted d-block mt-1">
                                        <a href="{{ asset($bill->document) }}" target="_blank">View current document</a>
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-4 mt-5 border-bottom pb-2">Payment Details</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Paid Amount (৳)</label>
                                <input type="number" name="paid_amount" value="{{ old('paid_amount', $bill->paid_amount) }}" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Payment Date</label>
                                <input type="date" name="payment_date" value="{{ old('payment_date', $bill->payment_date ? $bill->payment_date->format('Y-m-d') : '') }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Payment Method</label>
                                <select name="payment_method" class="form-select">
                                    <option value="">Not Paid Yet</option>
                                    <option value="cash" {{ old('payment_method', $bill->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bkash" {{ old('payment_method', $bill->payment_method) == 'bkash' ? 'selected' : '' }}>bKash</option>
                                    <option value="nagad" {{ old('payment_method', $bill->payment_method) == 'nagad' ? 'selected' : '' }}>Nagad</option>
                                    <option value="rocket" {{ old('payment_method', $bill->payment_method) == 'rocket' ? 'selected' : '' }}>Rocket</option>
                                    <option value="bank" {{ old('payment_method', $bill->payment_method) == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="other" {{ old('payment_method', $bill->payment_method) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Transaction Reference</label>
                                <input type="text" name="transaction_reference" value="{{ old('transaction_reference', $bill->transaction_reference) }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="input__group mb-3">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes', $bill->notes) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-blue">Update Utility Bill</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
