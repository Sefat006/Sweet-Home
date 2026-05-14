@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Collect Payment</h2>
                        <p style="color:white !important;" class="mb-0">
                            {{ date('F Y', strtotime($bill->bill_month . '-01')) }} | Flat: <strong>{{ $flat->flat_name }}</strong>
                        </p>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <a href="{{ route('admin.bills.index', [$building->id, $flat->id]) }}" class="btn btn-secondary">
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
        <div class="col-md-4">
            <div class="card bg-style mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-4 border-bottom pb-2">Bill Summary</h5>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
                            Grand Total
                            <span class="fw-bold">৳ {{ number_format($bill->total_amount + $bill->previous_due, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
                            Already Paid
                            <span class="text-success fw-bold">৳ {{ number_format($bill->paid_amount, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent fs-5">
                            Remaining Due
                            <span class="text-danger fw-bold">৳ {{ number_format($bill->remaining_amount, 2) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="form-vertical__item bg-style mb-30">
                <h5 class="mb-4">Payment Information</h5>
                <form action="{{ route('admin.bills.collect.store', [$building->id, $flat->id, $bill->id]) }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" style="padding: 10px 15px; border-radius: 5px 0 0 5px; border: 1px solid #ced4da; background: #e9ecef;">৳</span>
                                    <input type="number" name="amount" value="{{ old('amount', $bill->remaining_amount) }}" 
                                           class="form-control" style="border-radius: 0 5px 5px 0;" step="0.01" min="1" max="{{ $bill->remaining_amount }}" required>
                                </div>
                                @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Payment Date <span class="text-danger">*</span></label>
                                <input type="date" name="collection_date" value="{{ old('collection_date', date('Y-m-d')) }}" class="form-control" required>
                                @error('collection_date') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" class="form-control" required>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bkash" {{ old('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                                    <option value="nagad" {{ old('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                                    <option value="rocket" {{ old('payment_method') == 'rocket' ? 'selected' : '' }}>Rocket</option>
                                    <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('payment_method') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Transaction Reference</label>
                                <input type="text" name="transaction_reference" value="{{ old('transaction_reference') }}" class="form-control" placeholder="TrxID / Check No.">
                                @error('transaction_reference') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="input__group mb-3">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes about this payment">{{ old('notes') }}</textarea>
                        @error('notes') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-check"></i> Collect Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
