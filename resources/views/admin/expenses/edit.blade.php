@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Edit Expense — {{ date('F Y', strtotime($expense->expense_month . '-01')) }}</h2>
                        <p style="color:white !important;" class="mb-0">
                            Building: <strong>{{ $building->name }}</strong>
                        </p>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <a href="{{ route('admin.expenses.index', $building->id) }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="bg-style p-4 mb-4">
                <form action="{{ route('admin.expenses.update', [$building->id, $expense->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <h5 class="mb-4 border-bottom pb-2">Expense Details</h5>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Expense Month <span class="text-danger">*</span></label>
                            <input type="month" name="expense_month" value="{{ old('expense_month', $expense->expense_month) }}" class="form-control @error('expense_month') is-invalid @enderror" required>
                            @error('expense_month')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        @foreach($expenseFields as $field => $label)
                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ $label }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" name="{{ $field }}" value="{{ old($field, (float) $expense->{$field}) }}" class="form-control expense-field @error($field) is-invalid @enderror" step="0.01" min="0">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row mb-4 mt-2">
                        <div class="col-md-6 offset-md-6">
                            <label class="form-label fw-bold fs-5">Total Expense (৳)</label>
                            <input type="text" id="total_expense_display" class="form-control bg-light fw-bold fs-5 text-primary text-end" value="{{ number_format($expense->total_expense, 2) }}" readonly>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Any additional notes">{{ old('notes', $expense->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.expenses.index', $building->id) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-blue">Update Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const expenseInputs = document.querySelectorAll('.expense-field');
        const totalDisplay = document.getElementById('total_expense_display');

        function calculateTotal() {
            let total = 0;
            expenseInputs.forEach(input => {
                const val = parseFloat(input.value);
                if (!isNaN(val)) {
                    total += val;
                }
            });
            // Format number with commas
            totalDisplay.value = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(total);
        }

        // Add event listeners to all inputs
        expenseInputs.forEach(input => {
            input.addEventListener('input', calculateTotal);
        });

        // Calculate initial total in case old() values are populated
        calculateTotal();
    });
</script>
@endsection
