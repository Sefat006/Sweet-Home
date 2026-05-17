@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">

    {{-- ── Page Header ──────────────────────────────────────────────────── --}}
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Rent Overview</h2>
                        <p style="color:white !important;" class="mb-0">
                            Monthly rent &amp; bill status for all your flats
                        </p>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <a href="{{ route('admin.building.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-building"></i> Buildings
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Flash Messages ───────────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Summary Cards ────────────────────────────────────────────────── --}}
    @php
        $totalFlats    = $rows->count();
        $paidCount     = $rows->filter(fn($r) => $r['display_status'] === 'paid')->count();
        $dueCount      = $rows->filter(fn($r) => $r['display_status'] === 'due')->count();
        $partialCount  = $rows->filter(fn($r) => $r['display_status'] === 'partial')->count();
        $noBillCount   = $rows->filter(fn($r) => $r['display_status'] === 'no_bill')->count();
        $totalOutstandingAll = $rows->sum('total_outstanding');
    @endphp

    <div class="row mb-3 mt-3">
        <div class="col-6 col-md-2 mb-2">
            <div class="card text-center p-3 bg-style">
                <h4 class="mb-0">{{ $totalFlats }}</h4>
                <small class="text-muted">Total Flats</small>
            </div>
        </div>
        <div class="col-6 col-md-2 mb-2">
            <div class="card text-center p-3 bg-style">
                <h4 class="mb-0 text-danger">{{ $dueCount }}</h4>
                <small class="text-muted">Due</small>
            </div>
        </div>
        <div class="col-6 col-md-2 mb-2">
            <div class="card text-center p-3 bg-style">
                <h4 class="mb-0 text-warning">{{ $partialCount }}</h4>
                <small class="text-muted">Partial</small>
            </div>
        </div>
        <div class="col-6 col-md-2 mb-2">
            <div class="card text-center p-3 bg-style">
                <h4 class="mb-0 text-success">{{ $paidCount }}</h4>
                <small class="text-muted">Paid</small>
            </div>
        </div>
        <div class="col-6 col-md-2 mb-2">
            <div class="card text-center p-3 bg-style">
                <h4 class="mb-0 text-secondary">{{ $noBillCount }}</h4>
                <small class="text-muted">No Bill Yet</small>
            </div>
        </div>
        <div class="col-6 col-md-2 mb-2">
            <div class="card text-center p-3 bg-style">
                <h4 class="mb-0 text-danger">৳ {{ number_format($totalOutstandingAll, 0) }}</h4>
                <small class="text-muted">Total Outstanding</small>
            </div>
        </div>
    </div>

    {{-- ── Filters ──────────────────────────────────────────────────────── --}}
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card bg-style p-3">
                <form method="GET" action="{{ route('admin.rent.overview') }}" class="row g-2 align-items-end">
                    {{-- Address Filter --}}
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold mb-1">
                            <i class="fa-solid fa-location-dot me-1"></i>Filter by Address
                        </label>
                        <input
                            type="text"
                            name="address"
                            id="addressFilter"
                            list="addressSuggestions"
                            class="form-control form-control-sm"
                            placeholder="Type building address…"
                            value="{{ request('address') }}"
                            autocomplete="off"
                        >
                        <datalist id="addressSuggestions">
                            @foreach($addressSuggestions as $addr)
                                <option value="{{ $addr }}">
                            @endforeach
                        </datalist>
                    </div>

                    {{-- Payment Status Filter --}}
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold mb-1">
                            <i class="fa-solid fa-filter me-1"></i>Payment Status
                        </label>
                        <select name="payment_status" class="form-select form-select-sm">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>
                                Pending (Due / Partial)
                            </option>
                            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>
                                Paid
                            </option>
                        </select>
                    </div>

                    {{-- Occupancy Filter --}}
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold mb-1">
                            <i class="fa-solid fa-door-open me-1"></i>Occupancy
                        </label>
                        <select name="occupancy" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="occupied" {{ request('occupancy') === 'occupied' ? 'selected' : '' }}>
                                Occupied
                            </option>
                            <option value="vacant" {{ request('occupancy') === 'vacant' ? 'selected' : '' }}>
                                Vacant
                            </option>
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-blue btn-sm w-100">
                            <i class="fa-solid fa-magnifying-glass"></i> Filter
                        </button>
                        <a href="{{ route('admin.rent.overview') }}" class="btn btn-secondary btn-sm w-100">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Table ────────────────────────────────────────────────────────── --}}
    <div class="row">
        <div class="col-md-12">
            <div class="customers__area bg-style mb-30">
                <div class="customers__table table-responsive">
                    <table class="row-border table-style table" id="rentOverviewTable">
                        <thead>
                            <tr>
                                <th width="45">SL</th>
                                <th>Building</th>
                                <th>Flat</th>
                                <th>Tenant</th>
                                <th>Total Rent</th>
                                <th>Bill Status</th>
                                <th>Outstanding</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rows as $key => $row)
                                @php
                                    $status  = $row['display_status'];
                                    $rowClass = match($status) {
                                        'due'     => 'table-row-due',
                                        'partial' => 'table-row-partial',
                                        'paid'    => 'table-row-paid',
                                        default   => '',
                                    };
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    {{-- SL --}}
                                    <td>{{ $key + 1 }}</td>

                                    {{-- Building --}}
                                    <td>
                                        <strong>{{ $row['building']->name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fa-solid fa-location-dot me-1"></i>{{ $row['building']->address }}
                                        </small>
                                    </td>

                                    {{-- Flat --}}
                                    <td>
                                        <strong>{{ $row['flat']->flat_name }}</strong>
                                        @if($row['flat']->floor)
                                            <br><small class="text-muted">Floor: {{ $row['flat']->floor }}</small>
                                        @endif
                                    </td>

                                    {{-- Tenant --}}
                                    <td>
                                        @if($row['tenant_name'])
                                            <span class="fw-semibold">{{ $row['tenant_name'] }}</span>
                                            @if($row['tenant_phone'])
                                                <br><small class="text-muted"><i class="fa-solid fa-phone me-1"></i>{{ $row['tenant_phone'] }}</small>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Vacant</span>
                                        @endif
                                    </td>

                                    {{-- Total Rent --}}
                                    <td>
                                        <strong>৳ {{ number_format($row['total_rent'], 0) }}</strong>
                                        <br><small class="text-muted">/ month</small>
                                    </td>

                                    {{-- Bill Status --}}
                                    <td>
                                        @if($status === 'paid')
                                            <span class="badge bg-success px-2 py-1">
                                                <i class="fa-solid fa-circle-check me-1"></i>Paid
                                            </span>
                                        @elseif($status === 'due')
                                            <span class="badge bg-danger px-2 py-1">
                                                <i class="fa-solid fa-circle-xmark me-1"></i>Due
                                            </span>
                                            @if($row['overdue_count'] > 1)
                                                <br>
                                                <span class="badge mt-1" style="background:#7c2d12; font-size:0.72rem;">
                                                    <i class="fa-solid fa-triangle-exclamation me-1"></i>
                                                    {{ $row['overdue_count'] }} months overdue
                                                </span>
                                            @endif
                                        @elseif($status === 'partial')
                                            <span class="badge bg-warning text-dark px-2 py-1">
                                                <i class="fa-solid fa-circle-half-stroke me-1"></i>Partial
                                            </span>
                                            @if($row['overdue_count'] > 1)
                                                <br>
                                                <span class="badge mt-1" style="background:#78350f; font-size:0.72rem;">
                                                    <i class="fa-solid fa-triangle-exclamation me-1"></i>
                                                    {{ $row['overdue_count'] }} months overdue
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary px-2 py-1">
                                                <i class="fa-solid fa-clock me-1"></i>No Bill Yet
                                            </span>
                                        @endif

                                        {{-- Latest bill month label --}}
                                        @if($row['latest_bill'])
                                            <br>
                                            <small class="text-muted mt-1 d-inline-block">
                                                {{ \Carbon\Carbon::createFromFormat('Y-m', $row['latest_bill']->bill_month)->format('M Y') }}
                                            </small>
                                        @endif
                                    </td>

                                    {{-- Outstanding Amount --}}
                                    <td>
                                        @if($row['total_outstanding'] > 0)
                                            <span class="fw-bold text-danger">
                                                ৳ {{ number_format($row['total_outstanding'], 0) }}
                                            </span>
                                        @else
                                            <span class="text-success fw-semibold">—</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td>
                                        <div class="d-flex gap-2 align-items-center flex-wrap">
                                            {{-- View Flat --}}
                                            <a href="{{ route('admin.flats.show', [$row['building']->id, $row['flat']->id]) }}"
                                               title="View Flat" style="color:#2563eb;">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            {{-- View Bills --}}
                                            <a href="{{ route('admin.bills.index', [$row['building']->id, $row['flat']->id]) }}"
                                               title="View Bills" style="color:#d97706;">
                                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                            </a>

                                            {{-- Mark as Paid (only if there's a pending bill) --}}
                                            @if($row['latest_bill'] && $status !== 'paid' && $status !== 'no_bill')
                                                <button type="button"
                                                    class="btn-mark-paid"
                                                    style="border:none;background:none;color:#16a34a;cursor:pointer;"
                                                    title="Mark Latest Bill as Paid"
                                                    data-bill-id="{{ $row['latest_bill']->id }}"
                                                    data-flat-name="{{ $row['flat']->flat_name }}"
                                                    data-month="{{ \Carbon\Carbon::createFromFormat('Y-m', $row['latest_bill']->bill_month)->format('M Y') }}"
                                                    data-amount="{{ number_format($row['latest_bill']->remaining_amount, 0) }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#markPaidModal">
                                                    <i class="fa-solid fa-circle-check"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fa-solid fa-inbox fa-2x text-muted mb-2 d-block"></i>
                                        No flats found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Mark Paid Confirmation Modal ─────────────────────────────────────── --}}
<div class="modal fade" id="markPaidModal" tabindex="-1" aria-labelledby="markPaidModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-style">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="markPaidModalLabel">
                    <i class="fa-solid fa-circle-check text-success me-2"></i>Mark Bill as Paid
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-1">You are about to mark the following bill as <strong>fully paid</strong>:</p>
                <ul class="list-unstyled mt-2">
                    <li><strong>Flat:</strong> <span id="modalFlatName"></span></li>
                    <li><strong>Month:</strong> <span id="modalMonth"></span></li>
                    <li><strong>Outstanding:</strong> ৳ <span id="modalAmount"></span></li>
                </ul>
                <p class="text-warning small mb-0">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i>
                    This will create a cash payment record for the full remaining amount. You can view details in the Bills section.
                </p>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="markPaidForm" method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-circle-check me-1"></i>Confirm — Mark as Paid
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Row tinting */
    .table-row-due td       { border-left: 3px solid #ef4444; }
    .table-row-partial td   { border-left: 3px solid #f59e0b; }
    .table-row-paid td      { border-left: 3px solid #22c55e; opacity: 0.85; }

    /* Smooth hover on action buttons */
    .btn-mark-paid:hover i { transform: scale(1.25); transition: transform 0.15s ease; }

    #rentOverviewTable th { white-space: nowrap; }

    /* Dark-mode friendly form controls */
    .form-control, .form-select {
        background-color: rgba(255,255,255,0.07);
        border-color: rgba(255,255,255,0.15);
        color: inherit;
    }
    .form-control::placeholder { color: rgba(255,255,255,0.4); }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal       = document.getElementById('markPaidModal');
    const flatNameEl  = document.getElementById('modalFlatName');
    const monthEl     = document.getElementById('modalMonth');
    const amountEl    = document.getElementById('modalAmount');
    const formEl      = document.getElementById('markPaidForm');

    document.querySelectorAll('.btn-mark-paid').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const billId    = btn.dataset.billId;
            const flatName  = btn.dataset.flatName;
            const month     = btn.dataset.month;
            const amount    = btn.dataset.amount;

            flatNameEl.textContent = flatName;
            monthEl.textContent    = month;
            amountEl.textContent   = amount;
            formEl.action = '{{ url("admin/rent-overview/toggle-paid") }}/' + billId;
        });
    });
});
</script>
@endpush
@endsection
