@extends('front.layouts.app')


@section('content')

    <section class="hero d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-7">
                    <span class="badge bg-light text-primary border border-primary px-3 py-2 mb-3 rounded-pill">Affordable & Easy to Use</span>
                    <h1>Smart Property Management <br><span>On A Budget</span></h1>
                    <p>Stop paying for expensive and complicated software. Sweet Home provides a cost-effective way to track rents, handle utility bills, and monitor tenant data without stress.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a  href="{{route('login')}}" class="btn btn-primary btn-lg px-4 shadow-sm">Get Started</a>a
                        <button class="btn btn-outline-secondary btn-lg px-4 shadow-sm"><i class="fas fa-play-circle me-1"></i> Watch Demo</button>
                    </div>
                </div>
                <div class="col-lg-5">
                    <img src="https://img.freepik.com/free-vector/apartment-rent-concept-illustration_114360-5443.jpg" alt="Sweet Home Dashboard Preview" class="img-fluid rounded shadow-lg border">
                </div>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container text-center mb-5">
            <h2 class="fw-bold text-dark">Why Choose Sweet Home?</h2>
            <p class="text-secondary">Your complete property management partner</p>
        </div>
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box"><i class="fas fa-id-card"></i></div>
                        <h4 class="fw-semibold text-dark mb-3">Tenant Profiles</h4>
                        <p class="text-secondary mb-0">Securely store tenant identification, contact info, and family details in a clean, digital format.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box"><i class="fas fa-calculator"></i></div>
                        <h4 class="fw-semibold text-dark mb-3">Automated Billing</h4>
                        <p class="text-secondary mb-0">Calculate rents and utility invoices automatically with tracking for both full and partial payments.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box"><i class="fas fa-file-contract"></i></div>
                        <h4 class="fw-semibold text-dark mb-3">Documentation Reminders</h4>
                        <p class="text-secondary mb-0">Never miss a deadline. Store tax documents, contracts, and floor plans with expiry reminders.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="roles-section py-5">
        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-md-6 order-md-2 ps-lg-5">
                    <h2 class="fw-bold text-dark mb-4">Role-Based Access Control</h2>
                    <p class="text-secondary mb-4">Easily control what your staff can see and do using a simple, permission-based design.</p>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fas fa-check-circle text-primary mt-1 me-3 fs-5"></i>
                            <div>
                                <h5 class="fw-semibold mb-1 text-dark">House Owner (Admin)</h5>
                                <p class="text-secondary mb-0">Full, complete control to view and edit custom properties, track rents, and invite staff.</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="fas fa-check-circle text-primary mt-1 me-3 fs-5"></i>
                            <div>
                                <h5 class="fw-semibold mb-1 text-dark">Manager / Staff</h5>
                                <p class="text-secondary mb-0">Restricted access; can only see and update specific units/properties assigned by the owner.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 order-md-1 text-center">
                    <img src="https://img.freepik.com/free-vector/business-team-discussing-ideas-startup_74855-4380.jpg"
                        alt="Team roles and collaboration in property management"
                        class="img-fluid w-75 shadow-sm">
                </div>
            </div>
        </div>
    </section>

@endsection