@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Utility Bills</h2>
                        <p style="color:white !important;" class="mb-0">
                            Building: <strong>{{ $building->name }}</strong>
                        </p>
                    </div>
                </div>
                <div class="breadcrumb__content__right d-flex gap-2">
                    <a href="{{ route('admin.building.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Buildings
                    </a>
                    <a href="{{ route('admin.utility.create', $building->id) }}" class="btn btn-blue">
                        <i class="fa-solid fa-plus"></i> Add Utility Bill
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card text-center p-3 bg-style border-danger" style="border-left: 4px solid #ef4444;">
                <h5 class="mb-1 text-danger">৳ {{ number_format($summary['total_due'], 2) }}</h5>
                <small class="text-muted">Total Due</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-3 bg-style border-warning" style="border-left: 4px solid #f59e0b;">
                <h5 class="mb-1 text-warning">৳ {{ number_format($summary['total_partial'], 2) }}</h5>
                <small class="text-muted">Total Partial Remaining</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-3 bg-style border-success" style="border-left: 4px solid #10b981;">
                <h5 class="mb-1 text-success">{{ $summary['total_paid'] }}</h5>
                <small class="text-muted">Fully Paid Bills</small>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card bg-style">
                <div class="card-body">
                    <form action="{{ route('admin.utility.index', $building->id) }}" method="GET" class="d-flex gap-3 align-items-end flex-wrap">
                        <div class="flex-grow-1">
                            <label class="form-label">Filter by Type</label>
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="wasa" {{ request('type') == 'wasa' ? 'selected' : '' }}>WASA</option>
                                <option value="titas_gas" {{ request('type') == 'titas_gas' ? 'selected' : '' }}>TITAS Gas</option>
                                <option value="holding_tax" {{ request('type') == 'holding_tax' ? 'selected' : '' }}>Holding Tax</option>
                                <option value="electricity" {{ request('type') == 'electricity' ? 'selected' : '' }}>Electricity</option>
                                <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="flex-grow-1">
                            <label class="form-label">Filter by Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="due" {{ request('status') == 'due' ? 'selected' : '' }}>Due</option>
                                <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> Filter</button>
                            <a href="{{ route('admin.utility.index', $building->id) }}" class="btn btn-secondary">Reset</a>
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
                                <th>Type & Name</th>
                                <th>Period</th>
                                <th>Invoice / Due</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bills as $bill)
                            <tr>
                                <td>
                                    <strong>{{ \App\Models\UtilityBill::billTypeLabel($bill->bill_type) }}</strong><br>
                                    <small class="text-muted">{{ $bill->billing_name }}</small>
                                </td>
                                <td>
                                    @if($bill->bill_month)
                                        {{ date('M Y', strtotime($bill->bill_month . '-01')) }}
                                    @endif
                                    @if($bill->bill_year)
                                        <br>Year: {{ $bill->bill_year }}
                                    @endif
                                </td>
                                <td>
                                    @if($bill->invoice_number)
                                        Inv: {{ $bill->invoice_number }}<br>
                                    @endif
                                    @if($bill->due_date)
                                        <span class="{{ $bill->due_date < now() && $bill->payment_status !== 'paid' ? 'text-danger fw-bold' : '' }}">
                                            Due: {{ $bill->due_date->format('d M, Y') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    Total: ৳ {{ number_format($bill->total_amount, 2) }}<br>
                                    <span class="text-success">Paid: ৳ {{ number_format($bill->paid_amount, 2) }}</span><br>
                                    <span class="text-danger">Due: ৳ {{ number_format($bill->remaining_amount, 2) }}</span>
                                </td>
                                <td>
                                    @if($bill->payment_status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($bill->payment_status === 'partial')
                                        <span class="badge bg-warning text-dark">Partial</span>
                                    @else
                                        <span class="badge bg-danger">Due</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2 align-items-center flex-wrap">
                                        @if($bill->document)
                                            <a href="{{ asset($bill->document) }}" target="_blank" title="View Document" style="color:#0ea5e9;">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </a>
                                        @endif

                                        {{-- Edit / Manage Payment --}}
                                        <a href="{{ route('admin.utility.edit', [$building->id, $bill->id]) }}" title="Edit & Pay" style="color:#16a34a;">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        {{-- Mark as Paid --}}
                                        @if($bill->payment_status !== 'paid')
                                            <form action="{{ route('admin.utility.mark-paid', [$building->id, $bill->id]) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Mark this entire bill as paid by Cash today?')">
                                                @csrf
                                                <input type="hidden" name="payment_date" value="{{ date('Y-m-d') }}">
                                                <input type="hidden" name="payment_method" value="cash">
                                                <button type="submit" style="border:none;background:none;color:#10b981;" title="Mark as fully paid">
                                                    <i class="fa-solid fa-check-double"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        {{-- Delete --}}
                                        <form action="{{ route('admin.utility.destroy', [$building->id, $bill->id]) }}"
                                              method="POST" style="display:inline-block;"
                                              onsubmit="return confirm('Delete this utility bill?')">
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
                                <td colspan="6" class="text-center">No utility bills found.</td>
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
