@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Bill Details</h2>
                        <p style="color:white !important;" class="mb-0">
                            {{ date('F Y', strtotime($bill->bill_month . '-01')) }} | Flat: <strong>{{ $flat->flat_name }}</strong>
                        </p>
                    </div>
                </div>
                <div class="breadcrumb__content__right d-flex gap-2">
                    <a href="{{ route('admin.bills.index', [$building->id, $flat->id]) }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Bills
                    </a>
                    @if($bill->collection_status !== 'paid')
                        <a href="{{ route('admin.bills.collect.form', [$building->id, $flat->id, $bill->id]) }}" class="btn btn-blue">
                            <i class="fa-solid fa-money-bill-wave"></i> Collect Payment
                        </a>
                    @endif
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
        <div class="col-md-5">
            <div class="card bg-style mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-4">Rent Breakdown</h5>
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th>House Rent</th>
                                <td class="text-end">৳ {{ number_format($bill->house_rent, 2) }}</td>
                            </tr>
                            <tr>
                                <th>WASA</th>
                                <td class="text-end">৳ {{ number_format($bill->wasa, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Common Electricity</th>
                                <td class="text-end">৳ {{ number_format($bill->common_electricity, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Gas</th>
                                <td class="text-end">৳ {{ number_format($bill->gas, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Utility</th>
                                <td class="text-end">৳ {{ number_format($bill->utility, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Parking</th>
                                <td class="text-end">৳ {{ number_format($bill->parking, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Society Bill</th>
                                <td class="text-end">৳ {{ number_format($bill->society_bill, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Security</th>
                                <td class="text-end">৳ {{ number_format($bill->security, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Other</th>
                                <td class="text-end">৳ {{ number_format($bill->other, 2) }}</td>
                            </tr>
                            <tr class="bg-light">
                                <th><strong>Current Month Total</strong></th>
                                <td class="text-end"><strong>৳ {{ number_format($bill->total_amount, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th class="text-danger">Previous Due</th>
                                <td class="text-end text-danger">৳ {{ number_format($bill->previous_due, 2) }}</td>
                            </tr>
                            <tr class="bg-primary text-white" style="background-color: #0d6efd !important; color: white;">
                                <th><strong>Grand Total</strong></th>
                                <td class="text-end"><strong>৳ {{ number_format($bill->total_amount + $bill->previous_due, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card bg-style mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-4">Summary & Status</h5>
                    <div class="row text-center mb-4">
                        <div class="col-4 border-end">
                            <h6 class="text-muted">Total Paid</h6>
                            <h4 class="text-success">৳ {{ number_format($bill->paid_amount, 2) }}</h4>
                        </div>
                        <div class="col-4 border-end">
                            <h6 class="text-muted">Remaining Due</h6>
                            <h4 class="text-danger">৳ {{ number_format($bill->remaining_amount, 2) }}</h4>
                        </div>
                        <div class="col-4">
                            <h6 class="text-muted">Status</h6>
                            <h4>
                                @if($bill->collection_status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($bill->collection_status === 'partial')
                                    <span class="badge bg-warning text-dark">Partial</span>
                                @else
                                    <span class="badge bg-danger">Due</span>
                                @endif
                            </h4>
                        </div>
                    </div>

                    <h5 class="card-title mb-3">Collection History</h5>
                    @if($bill->collections->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Method</th>
                                        <th>Ref / Notes</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bill->collections as $collection)
                                        <tr>
                                            <td>{{ $collection->collection_date->format('d M, Y') }}</td>
                                            <td><span class="text-uppercase">{{ $collection->payment_method }}</span></td>
                                            <td>
                                                @if($collection->transaction_reference)
                                                    <small>Ref: {{ $collection->transaction_reference }}</small><br>
                                                @endif
                                                <small class="text-muted">{{ $collection->notes }}</small>
                                            </td>
                                            <td class="text-success fw-bold">৳ {{ number_format($collection->amount, 2) }}</td>
                                            <td>
                                                <form action="{{ route('admin.bills.collection.delete', [$building->id, $flat->id, $bill->id, $collection->id]) }}"
                                                      method="POST" onsubmit="return confirm('Remove this payment collection?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">No payments have been collected yet.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
