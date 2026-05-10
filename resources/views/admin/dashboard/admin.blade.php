@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-12 mb-4">
        <h4 class="fw-bold mb-0">Welcome, {{ auth()->user()->name }}</h4>
        <p class="text-muted">Admin ID: {{ auth()->user()->admin_id }}</p>
    </div>
</div>

{{-- Profile incomplete warning --}}
@if(!auth()->user()->profile_completed)
<div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <div>
        Your profile is incomplete. 
        <a href="{{ route('admin.profile.edit') }}" class="fw-semibold alert-link">Complete your profile</a> 
        to unlock all features.
    </div>
</div>
@endif

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1 small">Total Buildings</p>
                <h4 class="fw-bold mb-0">0</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1 small">Total Flats</p>
                <h4 class="fw-bold mb-0">0</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1 small">Occupied</p>
                <h4 class="fw-bold text-success mb-0">0</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1 small">Vacant</p>
                <h4 class="fw-bold text-danger mb-0">0</h4>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Collection This Month</div>
            <div class="card-body">
                <p class="text-muted mb-0">No data yet.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Due Amount</div>
            <div class="card-body">
                <p class="text-muted mb-0">No data yet.</p>
            </div>
        </div>
    </div>
</div>

@endsection
