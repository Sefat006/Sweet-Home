@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-12 mb-4">
        <h4 class="fw-bold mb-0">Super Admin Dashboard</h4>
        <p class="text-muted">System overview and admin approvals</p>
    </div>
</div>

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1 small">Total Admins</p>
                <h4 class="fw-bold mb-0">0</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1 small">Pending Approvals</p>
                <h4 class="fw-bold text-warning mb-0">0</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1 small">Total Managers</p>
                <h4 class="fw-bold mb-0">0</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1 small">Total Buildings</p>
                <h4 class="fw-bold mb-0">0</h4>
            </div>
        </div>
    </div>
</div>

{{-- Pending Admin Approvals Table --}}
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                Pending Admin Registrations
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">No pending approvals.</p>
                {{-- এখানে পরে pending admins table আসবে --}}
            </div>
        </div>
    </div>
</div>

@endsection
