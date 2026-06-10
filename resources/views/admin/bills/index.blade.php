@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Monthly Bills</h2>
                        <p style="color:white !important;" class="mb-0">
                            Flat: <strong>{{ $flat->flat_name }}</strong> &nbsp;|&nbsp; Building: {{ $building->name }}
                        </p>
                    </div>
                </div>
                <div class="breadcrumb__content__right d-flex gap-2">
                    <a href="{{ route('admin.rent.overview')}}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Rent Overview
                    </a>
                    <a href="{{ route('admin.flats.index', $building->id) }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Flats
                    </a>
                    <a href="{{ route('admin.bills.create', [$building->id, $flat->id]) }}" class="btn btn-blue">
                        <i class="fa-solid fa-file-invoice-dollar"></i> Generate Bill
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card text-center p-3 bg-style border-danger" style="border-left: 4px solid #ef4444;">
                <h5 class="mb-1 text-danger">৳ {{ number_format($totalDue, 2) }}</h5>
                <small class="text-muted">Total Due Bills Amount</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-3 bg-style border-warning" style="border-left: 4px solid #f59e0b;">
                <h5 class="mb-1 text-warning">৳ {{ number_format($totalPartial, 2) }}</h5>
                <small class="text-muted">Total Partial Remaining</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-3 bg-style border-success" style="border-left: 4px solid #10b981;">
                <h5 class="mb-1 text-success">{{ $totalPaid }}</h5>
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

    <div class="row">
        <div class="col-md-12">
            <div class="customers__area bg-style mb-30">
                <div class="customers__table table-responsive">
                    <table class="row-border table-style table">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Tenant</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Remaining</th>
                                <th>Status</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bills as $bill)
                            <tr>
                                <td>
                                    <strong>{{ date('F Y', strtotime($bill->bill_month . '-01')) }}</strong>
                                </td>
                                <td>{{ $bill->tenant ? $bill->tenant->name : 'N/A' }}</td>
                                <td>৳ {{ number_format($bill->total_amount + $bill->previous_due, 2) }}</td>
                                <td><span class="text-success">৳ {{ number_format($bill->paid_amount, 2) }}</span></td>
                                <td><span class="text-danger">৳ {{ number_format($bill->remaining_amount, 2) }}</span></td>
                                <td>
                                    @if($bill->collection_status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($bill->collection_status === 'partial')
                                        <span class="badge bg-warning text-dark">Partial</span>
                                    @else
                                        <span class="badge bg-danger">Due</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2 align-items-center flex-wrap">
                                        {{-- View --}}
                                        <a href="{{ route('admin.bills.show', [$building->id, $flat->id, $bill->id]) }}"
                                           title="View Details" style="color:#2563eb;">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        
                                        {{-- Collect --}}
                                        @if($bill->collection_status !== 'paid')
                                            <a href="{{ route('admin.bills.collect.form', [$building->id, $flat->id, $bill->id]) }}"
                                               title="Collect Payment" style="color:#16a34a;">
                                                <i class="fa-solid fa-money-bill-wave"></i>
                                            </a>
                                        @endif
                                        
                                        {{-- Delete --}}
                                        <form action="{{ route('admin.bills.destroy', [$building->id, $flat->id, $bill->id]) }}"
                                              method="POST" style="display:inline-block;"
                                              onsubmit="return confirm('Delete this bill and all its collections?')">
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
                                <td colspan="7" class="text-center">No bills generated for this flat yet.</td>
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
