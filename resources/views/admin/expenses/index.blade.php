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
                        <h2 style="color:white !important;">Building Expenses</h2>
                        <p style="color:white !important;" class="mb-0">
                            Building: <strong>{{ $building->name }}</strong>
                        </p>
                    </div>
                </div>
                <div class="breadcrumb__content__right d-flex gap-2">
                    <a href="{{ route('admin.building.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back to Buildings
                    </a>
                    <a href="{{ route('admin.expenses.create', $building->id) }}" class="btn btn-blue">
                        <i class="fa-solid fa-plus"></i> Add Expense
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card text-center p-3 bg-style border-info" style="border-left: 4px solid #0ea5e9;">
                <h5 class="mb-1 text-info">{{ $expenses->count() }}</h5>
                <small class="text-muted">Total Records</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-center p-3 bg-style border-primary" style="border-left: 4px solid #3b82f6;">
                <h5 class="mb-1 text-primary">৳ {{ number_format($yearlyTotal, 0) }}</h5>
                <small class="text-muted">Total Expense</small>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card bg-style">
                <div class="card-body">
                    <form action="{{ route('admin.expenses.index', $building->id) }}" method="GET" class="d-flex gap-3 align-items-end flex-wrap">
                        <div class="flex-grow-1">
                            <label class="form-label">Filter by Year</label>
                            <select name="year" class="form-select" onchange="this.form.submit()">
                                <option value="">All Years</option>
                                @foreach($years as $yr)
                                    <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <noscript><button type="submit" class="btn btn-primary">Filter</button></noscript>
                            <a href="{{ route('admin.expenses.index', $building->id) }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="customers__area bg-style mb-30">
                <div class="customers__table table-responsive">
                    <table class="row-border table-style table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Month</th>
                                <th>Security</th>
                                <th>Cleaning</th>
                                <th>Cleaning Mat.</th>
                                <th>Maintenance</th>
                                <th>Eid Bonus</th>
                                <th>Material Rep.</th>
                                <th>Flat Cleaning</th>
                                <th>Society Cost</th>
                                <th>Driver Cost</th>
                                <th>Other</th>
                                <th>Total</th>
                                <th width="100">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $key => $expense)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="text-nowrap fw-bold">{{ date('F Y', strtotime($expense->expense_month . '-01')) }}</td>
                                <td>{{ $expense->security_bill > 0 ? '৳ ' . number_format($expense->security_bill, 0) : '—' }}</td>
                                <td>{{ $expense->cleaning_bill > 0 ? '৳ ' . number_format($expense->cleaning_bill, 0) : '—' }}</td>
                                <td>{{ $expense->cleaning_material > 0 ? '৳ ' . number_format($expense->cleaning_material, 0) : '—' }}</td>
                                <td>{{ $expense->maintenance > 0 ? '৳ ' . number_format($expense->maintenance, 0) : '—' }}</td>
                                <td>{{ $expense->eid_bonus > 0 ? '৳ ' . number_format($expense->eid_bonus, 0) : '—' }}</td>
                                <td>{{ $expense->material_replacement > 0 ? '৳ ' . number_format($expense->material_replacement, 0) : '—' }}</td>
                                <td>{{ $expense->flat_cleaning > 0 ? '৳ ' . number_format($expense->flat_cleaning, 0) : '—' }}</td>
                                <td>{{ $expense->society_cost > 0 ? '৳ ' . number_format($expense->society_cost, 0) : '—' }}</td>
                                <td>{{ $expense->driver_cost > 0 ? '৳ ' . number_format($expense->driver_cost, 0) : '—' }}</td>
                                <td>{{ $expense->other > 0 ? '৳ ' . number_format($expense->other, 0) : '—' }}</td>
                                <td class="fw-bold text-primary text-nowrap">৳ {{ number_format($expense->total_expense, 0) }}</td>
                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        {{-- Edit --}}
                                        <a href="{{ route('admin.expenses.edit', [$building->id, $expense->id]) }}" title="Edit" style="color:#16a34a;">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        {{-- Delete --}}
                                        <form action="{{ route('admin.expenses.destroy', [$building->id, $expense->id]) }}"
                                              method="POST" style="display:inline-block;"
                                              onsubmit="return confirm('Delete this expense record?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" style="border:none;background:none;color:red;" title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="14" class="text-center">No expense records found.</td>
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
