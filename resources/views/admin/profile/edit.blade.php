@extends('admin.layouts.app')

@push('styles')
<style>
    /* ── Variables ─────────────────────────────────────────────── */
    :root {
        --pf-accent: #2563eb;
        --pf-accent-lt: #eff6ff;
        --pf-danger: #dc2626;
        --pf-border: #e2e8f0;
        --pf-label: #374151;
        --pf-muted: #6b7280;
        --pf-bg: #f8fafc;
        --pf-card: #ffffff;
        --pf-radius: 10px;
        --pf-shadow: 0 1px 4px rgba(0, 0, 0, .07);
    }

    /* ── Layout ────────────────────────────────────────────────── */
    .pf-body {
        width: 100%;
    }

    /* ── Section card ──────────────────────────────────────────── */
    .pf-section {
        background: var(--pf-card);
        border: 1px solid var(--pf-border);
        border-radius: var(--pf-radius);
        box-shadow: var(--pf-shadow);
        margin-bottom: 24px;
        scroll-margin-top: 80px;
    }

    .pf-section__head {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 24px;
        border-bottom: 1px solid var(--pf-border);
    }

    .pf-section__num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--pf-accent);
        color: #fff;
        font-size: .78rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .pf-section__title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .pf-section__body {
        padding: 24px;
    }

    /* ── Sub-section divider ───────────────────────────────────── */
    .pf-sub {
        margin-bottom: 24px;
    }

    .pf-sub:last-child {
        margin-bottom: 0;
    }

    .pf-sub__label {
        font-size: .71rem;
        font-weight: 700;
        letter-spacing: .09em;
        text-transform: uppercase;
        color: var(--pf-muted);
        padding-bottom: 8px;
        border-bottom: 1px solid var(--pf-border);
        margin-bottom: 16px;
    }

    /* ── Field label ───────────────────────────────────────────── */
    .pf-label {
        font-size: .82rem;
        font-weight: 600;
        color: var(--pf-label);
        margin-bottom: 5px;
        display: block;
    }

    .pf-required {
        color: var(--pf-danger);
        margin-left: 2px;
    }

    /* ── Controls ──────────────────────────────────────────────── */
    .form-control,
    .form-select {
        font-size: .85rem;
        border-color: var(--pf-border);
        border-radius: 7px;
        color: #1e293b;
        padding: 8px 12px;
        transition: border-color .15s, box-shadow .15s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--pf-accent);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
        outline: none;
    }

    textarea.form-control {
        resize: vertical;
    }

    /* ── File upload box ───────────────────────────────────────── */
    .pf-file {
        border: 2px dashed var(--pf-border);
        border-radius: 8px;
        padding: 13px 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: border-color .15s, background .15s;
        background: #fafbfd;
        min-height: 56px;
    }

    .pf-file:hover {
        border-color: var(--pf-accent);
        background: var(--pf-accent-lt);
    }

    .pf-file__icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: var(--pf-accent-lt);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: background .15s;
    }

    .pf-file:hover .pf-file__icon {
        background: rgba(37, 99, 235, .18);
    }

    .pf-file__icon svg {
        width: 17px;
        height: 17px;
        stroke: var(--pf-accent);
    }

    .pf-file__text {
        flex: 1;
        min-width: 0;
    }

    .pf-file__cta {
        font-size: .8rem;
        font-weight: 600;
        color: var(--pf-accent);
        line-height: 1.3;
    }

    .pf-file__hint {
        font-size: .73rem;
        color: var(--pf-muted);
        line-height: 1.3;
    }

    .pf-file__existing {
        font-size: .73rem;
        color: #16a34a;
        font-weight: 500;
        margin-top: 2px;
    }

    .pf-file__name {
        font-size: .78rem;
        color: #1e293b;
        font-weight: 500;
        margin-top: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: none;
    }

    .pf-file__name.show {
        display: block;
    }

    /* ── Dynamic sub-card (spouse / child) ─────────────────────── */
    .pf-subcard {
        background: var(--pf-bg);
        border: 1px solid var(--pf-border);
        border-radius: 8px;
        padding: 18px;
        margin-bottom: 14px;
    }

    .pf-subcard:last-child {
        margin-bottom: 0;
    }

    .pf-subcard__head {
        font-size: .82rem;
        font-weight: 700;
        color: var(--pf-accent);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pf-subcard__head::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--pf-border);
    }

    /* ── Education grid row ─────────────────────────────────────── */
    .pf-edu-row {
        display: grid;
        grid-template-columns: 2fr 2fr 1.3fr 1fr 32px;
        gap: 8px;
        margin-bottom: 8px;
        align-items: center;
    }

    @media(max-width:768px) {
        .pf-edu-row {
            grid-template-columns: 1fr 1fr;
        }

        .pf-edu-row .btn-pf-del {
            grid-column: span 2;
            justify-self: start;
        }
    }

    /* ── Checkbox ──────────────────────────────────────────────── */
    .pf-check {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pf-check input {
        width: 16px;
        height: 16px;
        accent-color: var(--pf-accent);
        cursor: pointer;
        flex-shrink: 0;
    }

    .pf-check label {
        font-size: .83rem;
        color: var(--pf-label);
        cursor: pointer;
        margin: 0;
    }

    /* ── Owner banner ──────────────────────────────────────────── */
    .pf-banner {
        background: var(--pf-card);
        border: 1px solid var(--pf-border);
        border-radius: var(--pf-radius);
        padding: 16px 24px;
        margin-bottom: 24px;
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: center;
        box-shadow: var(--pf-shadow);
    }

    .pf-banner__avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--pf-accent);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .pf-banner__name {
        font-weight: 700;
        color: #1e293b;
        font-size: .95rem;
        line-height: 1.3;
    }

    .pf-banner__sub {
        font-size: .8rem;
        color: var(--pf-muted);
    }

    .pf-banner__meta {
        flex: 1;
    }

    .pf-badge {
        font-size: .74rem;
        background: var(--pf-accent-lt);
        color: var(--pf-accent);
        border-radius: 20px;
        padding: 3px 11px;
        font-weight: 600;
    }

    .pf-banner__badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }

    /* ── Add / delete buttons ──────────────────────────────────── */
    .btn-pf-add {
        font-size: .78rem;
        font-weight: 600;
        color: var(--pf-accent);
        background: var(--pf-accent-lt);
        border: 1px dashed var(--pf-accent);
        border-radius: 6px;
        padding: 5px 14px;
        cursor: pointer;
        transition: all .15s;
    }

    .btn-pf-add:hover {
        background: var(--pf-accent);
        color: #fff;
    }

    .btn-pf-del {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        background: #fee2e2;
        border: none;
        color: var(--pf-danger);
        font-size: .85rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all .15s;
    }

    .btn-pf-del:hover {
        background: var(--pf-danger);
        color: #fff;
    }

    /* ── Submit bar ────────────────────────────────────────────── */
    .pf-submit {
        background: var(--pf-card);
        border: 1px solid var(--pf-border);
        border-radius: var(--pf-radius);
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        box-shadow: var(--pf-shadow);
        margin-bottom: 32px;
    }

    .pf-submit__note {
        font-size: .8rem;
        color: var(--pf-muted);
    }

    .btn-pf-save {
        background: var(--pf-accent);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 32px;
        font-size: .88rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: background .15s;
    }

    .btn-pf-save:hover {
        background: #1d4ed8;
    }

    .btn-pf-save svg {
        width: 15px;
        height: 15px;
    }

    /* ── Alerts ────────────────────────────────────────────────── */
    .pf-alert-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        padding: 14px 18px;
        margin-bottom: 20px;
        font-size: .83rem;
        color: #16a34a;
        font-weight: 600;
    }

    .pf-errors {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 8px;
        padding: 14px 18px;
        margin-bottom: 20px;
    }

    .pf-errors ul {
        margin: 0;
        padding-left: 18px;
    }

    .pf-errors li {
        font-size: .83rem;
        color: var(--pf-danger);
    }

    .pf-empty-msg {
        font-size: .83rem;
        color: var(--pf-muted);
        margin: 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- Breadcrumb --}}
    <div class="breadcrumb__content mb-4">
        <div class="breadcrumb__content__left">
            <div class="breadcrumb__title">
                <h2>Edit Profile</h2>
            </div>
        </div>
        <div class="breadcrumb__content__right">
            <a href="{{ route('admin.profile.show') }}" class="btn btn-sm btn-outline-secondary">
                View Profile
            </a>
        </div>
    </div>

    {{-- Owner banner --}}
    <div class="pf-banner">
        <div class="pf-banner__avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div class="pf-banner__meta">
            <div class="pf-banner__name">{{ $user->name }}</div>
            <div class="pf-banner__sub">{{ $user->email }} &bull; {{ $user->phone }}</div>
        </div>
        <div class="pf-banner__badges">
            <span class="pf-badge">{{ strtoupper($user->admin_id ?? 'Admin') }}</span>
            <span class="pf-badge">Edit Profile</span>
        </div>
    </div>

    {{-- Success message --}}
    @if(session('success'))
    <div class="pf-alert-success">&#10003; {{ session('success') }}</div>
    @endif

    {{-- Validation errors --}}
    @if($errors->any())
    <div class="pf-errors">
        <strong style="font-size:.83rem;color:var(--pf-danger)">Please fix the following errors:</strong>
        <ul class="mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" id="profileForm">
        @csrf
        @method('PUT')

        <div class="pf-body">

            {{-- ─────────────────────────────────────────
                 SECTION 1 — Personal Information
            ───────────────────────────────────────── --}}
            <div class="pf-section" id="sec-personal">
                <div class="pf-section__head">
                    <span class="pf-section__num">1</span>
                    <h5 class="pf-section__title">Personal Information</h5>
                </div>
                <div class="pf-section__body">

                    <div class="pf-sub">
                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">Full Name</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">Email Address</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $user->phone) }}" required>
                        </div>
                        <div class="pf-sub__label">Profile Photo</div>
                        <div class="col-md-5 col-sm-8">
                            <label class="pf-label">Owner Photo</label>
                            <div class="pf-file" onclick="document.getElementById('f_owner_image').click()">
                                <div class="pf-file__icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 3h18M3 21h18" />
                                    </svg>
                                </div>
                                <div class="pf-file__text">
                                    <div class="pf-file__cta">Click to replace photo</div>
                                    <div class="pf-file__hint">JPG or PNG, max 2 MB</div>
                                    @if($user->image)
                                    <div class="pf-file__existing">&#10003; Current: {{ basename($user->image) }}</div>
                                    @endif
                                    <div class="pf-file__name" id="f_owner_image_name"></div>
                                </div>
                            </div>
                            <input type="file" id="f_owner_image" name="image" accept="image/*" class="d-none" onchange="pfFile(this,'f_owner_image_name')">
                        </div>
                    </div>

                    <div class="pf-sub" style="margin-bottom:0">
                        <div class="pf-sub__label">Basic Details</div>
                        <div class="row g-3">
                            <div class="col-md-4 col-sm-6">
                                <label class="pf-label">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control"
                                    value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <label class="pf-label">Blood Group</label>
                                <select name="blood_group" class="form-select">
                                    <option value="">— Select —</option>
                                    @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                    <option value="{{ $bg }}" @selected(old('blood_group', $user->blood_group) == $bg)>{{ $bg }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <label class="pf-label">Marital Status</label>
                                <select name="marital_status" class="form-select" id="marital_status_sel">
                                    <option value="">— Select —</option>
                                    <option value="single" @selected(old('marital_status', $user->marital_status) == 'single')>Single</option>
                                    <option value="married" @selected(old('marital_status', $user->marital_status) == 'married')>Married</option>
                                    <option value="divorced" @selected(old('marital_status', $user->marital_status) == 'divorced')>Divorced</option>
                                    <option value="widowed" @selected(old('marital_status', $user->marital_status) == 'widowed')>Widowed</option>
                                </select>
                            </div>
                            <div class="col-md-5 col-sm-8">
                                <label class="pf-label">Emergency Contact</label>
                                <input type="text" name="emergency_contact" class="form-control"
                                    placeholder="Full name — 017XXXXXXXX"
                                    value="{{ old('emergency_contact', $user->emergency_contact) }}">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ─────────────────────────────────────────
                 SECTION 2 — Address
            ───────────────────────────────────────── --}}
            <div class="pf-section" id="sec-address">
                <div class="pf-section__head">
                    <span class="pf-section__num">2</span>
                    <h5 class="pf-section__title">Address Information</h5>
                </div>
                <div class="pf-section__body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="pf-label">Present Address</label>
                            <textarea name="present_address" rows="3" class="form-control" placeholder="House, Road, Area, City">{{ old('present_address', $user->present_address) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="pf-label">Permanent Address</label>
                            <textarea name="permanent_address" rows="3" class="form-control" placeholder="Village, Thana, District">{{ old('permanent_address', $user->permanent_address) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─────────────────────────────────────────
                 SECTION 3 — Education & Occupation
            ───────────────────────────────────────── --}}
            <div class="pf-section" id="sec-education">
                <div class="pf-section__head">
                    <span class="pf-section__num">3</span>
                    <h5 class="pf-section__title">Education & Occupation</h5>
                </div>
                <div class="pf-section__body">

                    <div class="pf-sub">
                        <div class="pf-sub__label">Educational Qualifications</div>
                        <div id="edu_rows">
                            {{-- Existing rows pre-filled from DB --}}
                            @php
                            $educationData = is_array($user->education) ? $user->education : (json_decode($user->education, true) ?? []);
                            if (empty($educationData)) $educationData = [['exam'=>'','institute'=>'','date'=>'','year'=>'']];
                            @endphp
                            @foreach($educationData as $edu)
                            <div class="pf-edu-row">
                                <input type="text" name="edu_exam[]" class="form-control" placeholder="Degree / Exam (e.g. B.Sc)" value="{{ old('edu_exam.'.$loop->index, $edu['exam'] ?? '') }}">
                                <input type="text" name="edu_institute[]" class="form-control" placeholder="Institute / University" value="{{ old('edu_institute.'.$loop->index, $edu['institute'] ?? '') }}">
                                <input type="date" name="edu_date[]" class="form-control" title="Passing Date" value="{{ old('edu_date.'.$loop->index, $edu['date'] ?? '') }}">
                                <input type="number" name="edu_year[]" class="form-control" placeholder="Year" min="1970" max="2099" value="{{ old('edu_year.'.$loop->index, $edu['year'] ?? '') }}">
                                <button type="button" class="btn-pf-del" onclick="removeEduRow(this)" title="Remove">✕</button>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn-pf-add" onclick="addEduRow()">+ Add Qualification</button>
                        </div>
                        <div class="mt-3 col-md-6">
                            <label class="pf-label">Certificate / Transcript</label>
                            <div class="pf-file" onclick="document.getElementById('f_edu_doc').click()">
                                <div class="pf-file__icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                                    </svg>
                                </div>
                                <div class="pf-file__text">
                                    <div class="pf-file__cta">Attach document</div>
                                    <div class="pf-file__hint">PDF or Image, max 5 MB</div>
                                    <div class="pf-file__name" id="f_edu_doc_name"></div>
                                </div>
                            </div>
                            <input type="file" id="f_edu_doc" name="education_document" accept=".pdf,.jpg,.jpeg,.png" class="d-none" onchange="pfFile(this,'f_edu_doc_name')">
                        </div>
                    </div>

                    <div class="pf-sub" style="margin-bottom:0">
                        <div class="pf-sub__label">Occupation</div>
                        <div class="row g-3">
                            <div class="col-md-4 col-sm-6">
                                <label class="pf-label">Position / Designation</label>
                                <input type="text" name="occupation_position" class="form-control" placeholder="e.g. Senior Engineer"
                                    value="{{ old('occupation_position', $user->occupation_position) }}">
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <label class="pf-label">Company / Organisation</label>
                                <input type="text" name="occupation_company" class="form-control" placeholder="Company name"
                                    value="{{ old('occupation_company', $user->occupation_company) }}">
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label class="pf-label">Company Address</label>
                                <input type="text" name="occupation_address" class="form-control" placeholder="Office address"
                                    value="{{ old('occupation_address', $user->occupation_address) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="pf-label">Occupation Document</label>
                                <div class="pf-file" onclick="document.getElementById('f_occ_doc').click()">
                                    <div class="pf-file__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                                        </svg>
                                    </div>
                                    <div class="pf-file__text">
                                        <div class="pf-file__cta">Attach document</div>
                                        <div class="pf-file__hint">Employment letter / ID (PDF/Image)</div>
                                        @if($user->occupation_document)
                                        <div class="pf-file__existing">&#10003; Current file uploaded</div>
                                        @endif
                                        <div class="pf-file__name" id="f_occ_doc_name"></div>
                                    </div>
                                </div>
                                <input type="file" id="f_occ_doc" name="occupation_document" accept=".pdf,.jpg,.jpeg,.png" class="d-none" onchange="pfFile(this,'f_occ_doc_name')">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ─────────────────────────────────────────
                 SECTION 4 — ID Documents
            ───────────────────────────────────────── --}}
            <div class="pf-section" id="sec-documents">
                <div class="pf-section__head">
                    <span class="pf-section__num">4</span>
                    <h5 class="pf-section__title">Identification & Legal Documents</h5>
                </div>
                <div class="pf-section__body">

                    {{-- NID --}}
                    <div class="pf-sub">
                        <div class="pf-sub__label">National ID (NID)</div>
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4 col-sm-6">
                                <label class="pf-label">NID Number</label>
                                <input type="text" name="nid_number" class="form-control"
                                    value="{{ old('nid_number', $user->nid_number) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="pf-label">NID Document</label>
                                <div class="pf-file" onclick="document.getElementById('f_nid_doc').click()">
                                    <div class="pf-file__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                                        </svg>
                                    </div>
                                    <div class="pf-file__text">
                                        <div class="pf-file__cta">{{ $user->nid_document ? 'Replace NID document' : 'Attach NID' }}</div>
                                        <div class="pf-file__hint">Front & back scan (PDF/Image)</div>
                                        @if($user->nid_document)
                                        <div class="pf-file__existing">&#10003; Current: {{ basename($user->nid_document) }}</div>
                                        @endif
                                        <div class="pf-file__name" id="f_nid_doc_name"></div>
                                    </div>
                                </div>
                                <input type="file" id="f_nid_doc" name="nid_document" accept=".pdf,.jpg,.jpeg,.png" class="d-none" onchange="pfFile(this,'f_nid_doc_name')">
                            </div>
                        </div>
                    </div>

                    {{-- Passport --}}
                    <div class="pf-sub">
                        <div class="pf-sub__label">Passport</div>
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4 col-sm-6">
                                <label class="pf-label">Passport Number</label>
                                <input type="text" name="passport_number" class="form-control"
                                    value="{{ old('passport_number', $user->passport_number) }}">
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label class="pf-label">Valid Up To</label>
                                <input type="date" name="passport_expiry" class="form-control"
                                    value="{{ old('passport_expiry', $user->passport_expiry ? \Carbon\Carbon::parse($user->passport_expiry)->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-5">
                                <label class="pf-label">Passport Document</label>
                                <div class="pf-file" onclick="document.getElementById('f_pass_doc').click()">
                                    <div class="pf-file__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                                        </svg>
                                    </div>
                                    <div class="pf-file__text">
                                        <div class="pf-file__cta">Attach document</div>
                                        <div class="pf-file__hint">Bio-data page (PDF/Image)</div>
                                        @if($user->passport_document)
                                        <div class="pf-file__existing">&#10003; Current file uploaded</div>
                                        @endif
                                        <div class="pf-file__name" id="f_pass_doc_name"></div>
                                    </div>
                                </div>
                                <input type="file" id="f_pass_doc" name="passport_document" accept=".pdf,.jpg,.jpeg,.png" class="d-none" onchange="pfFile(this,'f_pass_doc_name')">
                            </div>
                        </div>
                    </div>

                    {{-- TIN --}}
                    <div class="pf-sub">
                        <div class="pf-sub__label">Tax Identification (TIN)</div>
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4 col-sm-6">
                                <label class="pf-label">TIN Number</label>
                                <input type="text" name="tin_number" class="form-control"
                                    value="{{ old('tin_number', $user->tin_number) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="pf-label">TIN Document</label>
                                <div class="pf-file" onclick="document.getElementById('f_tin_doc').click()">
                                    <div class="pf-file__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                                        </svg>
                                    </div>
                                    <div class="pf-file__text">
                                        <div class="pf-file__cta">Attach document</div>
                                        <div class="pf-file__hint">TIN certificate (PDF/Image)</div>
                                        @if($user->tin_document)
                                        <div class="pf-file__existing">&#10003; Current file uploaded</div>
                                        @endif
                                        <div class="pf-file__name" id="f_tin_doc_name"></div>
                                    </div>
                                </div>
                                <input type="file" id="f_tin_doc" name="tin_document" accept=".pdf,.jpg,.jpeg,.png" class="d-none" onchange="pfFile(this,'f_tin_doc_name')">
                            </div>
                        </div>
                    </div>

                    {{-- Driving Licence --}}
                    <div class="pf-sub" style="margin-bottom:0">
                        <div class="pf-sub__label">Driving Licence</div>
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4 col-sm-6">
                                <label class="pf-label">Licence Number</label>
                                <input type="text" name="driving_licence_number" class="form-control"
                                    value="{{ old('driving_licence_number', $user->driving_licence_number) }}">
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label class="pf-label">Valid Up To</label>
                                <input type="date" name="driving_licence_expiry" class="form-control"
                                    value="{{ old('driving_licence_expiry', $user->driving_licence_expiry ? \Carbon\Carbon::parse($user->driving_licence_expiry)->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-5">
                                <label class="pf-label">Licence Document</label>
                                <div class="pf-file" onclick="document.getElementById('f_dl_doc').click()">
                                    <div class="pf-file__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                                        </svg>
                                    </div>
                                    <div class="pf-file__text">
                                        <div class="pf-file__cta">Attach document</div>
                                        <div class="pf-file__hint">Licence scan (PDF/Image)</div>
                                        @if($user->driving_licence_document)
                                        <div class="pf-file__existing">&#10003; Current file uploaded</div>
                                        @endif
                                        <div class="pf-file__name" id="f_dl_doc_name"></div>
                                    </div>
                                </div>
                                <input type="file" id="f_dl_doc" name="driving_licence_document" accept=".pdf,.jpg,.jpeg,.png" class="d-none" onchange="pfFile(this,'f_dl_doc_name')">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ─────────────────────────────────────────
                 SECTION 5 — Father
            ───────────────────────────────────────── --}}
            <div class="pf-section" id="sec-father">
                <div class="pf-section__head">
                    <span class="pf-section__num">5</span>
                    <h5 class="pf-section__title">Father's Information</h5>
                </div>
                <div class="pf-section__body">
                    <div class="row g-3">
                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">Father's Name</label>
                            <input type="text" name="father_name" class="form-control" value="{{ old('father_name', $user->father_name) }}">
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="pf-label">Date of Birth</label>
                            <input type="date" name="father_dob" class="form-control"
                                value="{{ old('father_dob', $user->father_dob ? \Carbon\Carbon::parse($user->father_dob)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-2 col-sm-4">
                            <label class="pf-label">Status</label>
                            <select name="father_status" class="form-select">
                                <option value="">— Select —</option>
                                <option value="alive" @selected(old('father_status', $user->father_status) == 'alive')>Alive</option>
                                <option value="expired" @selected(old('father_status', $user->father_status) == 'expired')>Expired</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <label class="pf-label">Blood Group</label>
                            <select name="father_blood_group" class="form-select">
                                <option value="">— Select —</option>
                                @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                <option value="{{ $bg }}" @selected(old('father_blood_group', $user->father_blood_group) == $bg)>{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">Contact Number</label>
                            <input type="text" name="father_contact" class="form-control" value="{{ old('father_contact', $user->father_contact) }}">
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">Email Address</label>
                            <input type="email" name="father_email" class="form-control" value="{{ old('father_email', $user->father_email) }}">
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">Birth Certificate No.</label>
                            <input type="text" name="father_birth_certificate" class="form-control" value="{{ old('father_birth_certificate', $user->father_birth_certificate) }}">
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">NID Number</label>
                            <input type="text" name="father_nid_number" class="form-control" value="{{ old('father_nid_number', $user->father_nid_number) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="pf-label">Present Address</label>
                            <textarea name="father_present_address" rows="2" class="form-control" placeholder="Current address">{{ old('father_present_address', $user->father_present_address) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="pf-label">Permanent Address</label>
                            <textarea name="father_permanent_address" rows="2" class="form-control" placeholder="Permanent address">{{ old('father_permanent_address', $user->father_permanent_address) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="pf-label">Educational Qualifications</label>
                            <input type="text" name="father_education" class="form-control" placeholder="e.g. B.Sc, DU, 1985" value="{{ old('father_education', $user->father_education) }}">
                        </div>

                        <div class="col-12">
                            <div class="pf-sub__label" style="margin-top:4px">Occupation</div>
                        </div>

                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">Position</label>
                            <input type="text" name="father_occupation_position" class="form-control" value="{{ old('father_occupation_position', $user->father_occupation_position) }}">
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">Company / Organisation</label>
                            <input type="text" name="father_occupation_company" class="form-control" value="{{ old('father_occupation_company', $user->father_occupation_company) }}">
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">Company Address</label>
                            <input type="text" name="father_occupation_address" class="form-control" value="{{ old('father_occupation_address', $user->father_occupation_address) }}">
                        </div>
                        <div class="col-md-4 col-sm-6 d-flex align-items-end">
                            <div>
                                <label class="pf-label">Expiry Reminder</label>
                                <div class="pf-check">
                                    <input type="checkbox" name="father_reminder" value="1" id="father_reminder"
                                        {{ old('father_reminder', $user->father_reminder) ? 'checked' : '' }}>
                                    <label for="father_reminder">Enable expiry reminder</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─────────────────────────────────────────
                 SECTION 6 — Mother
            ───────────────────────────────────────── --}}
            <div class="pf-section" id="sec-mother">
                <div class="pf-section__head">
                    <span class="pf-section__num">6</span>
                    <h5 class="pf-section__title">Mother's Information</h5>
                </div>
                <div class="pf-section__body">
                    <div class="row g-3">
                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">Mother's Name</label>
                            <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name', $user->mother_name) }}">
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="pf-label">Date of Birth</label>
                            <input type="date" name="mother_dob" class="form-control"
                                value="{{ old('mother_dob', $user->mother_dob ? \Carbon\Carbon::parse($user->mother_dob)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-2 col-sm-4">
                            <label class="pf-label">Status</label>
                            <select name="mother_status" class="form-select" id="mother_status_sel">
                                <option value="">— Select —</option>
                                <option value="alive" @selected(old('mother_status', $user->mother_status) == 'alive')>Alive</option>
                                <option value="expired" @selected(old('mother_status', $user->mother_status) == 'expired')>Expired</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <label class="pf-label">Blood Group</label>
                            <select name="mother_blood_group" class="form-select">
                                <option value="">— Select —</option>
                                @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                <option value="{{ $bg }}" @selected(old('mother_blood_group', $user->mother_blood_group) == $bg)>{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Conditional: date of death --}}
                        <div class="col-md-4 col-sm-6" id="mother_expired_wrap"
                            style="{{ old('mother_status', $user->mother_status) == 'expired' ? '' : 'display:none' }}">
                            <label class="pf-label">Date of Death</label>
                            <input type="date" name="mother_expired_date" class="form-control"
                                value="{{ old('mother_expired_date', $user->mother_expired_date ? \Carbon\Carbon::parse($user->mother_expired_date)->format('Y-m-d') : '') }}">
                        </div>

                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">Contact Number</label>
                            <input type="text" name="mother_contact" class="form-control" value="{{ old('mother_contact', $user->mother_contact) }}">
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">Email Address</label>
                            <input type="email" name="mother_email" class="form-control" value="{{ old('mother_email', $user->mother_email) }}">
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">Birth Certificate No.</label>
                            <input type="text" name="mother_birth_certificate" class="form-control" value="{{ old('mother_birth_certificate', $user->mother_birth_certificate) }}">
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">NID Number</label>
                            <input type="text" name="mother_nid_number" class="form-control" value="{{ old('mother_nid_number', $user->mother_nid_number) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="pf-label">Present Address</label>
                            <textarea name="mother_present_address" rows="2" class="form-control" placeholder="Current address">{{ old('mother_present_address', $user->mother_present_address) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="pf-label">Permanent Address</label>
                            <textarea name="mother_permanent_address" rows="2" class="form-control" placeholder="Permanent address">{{ old('mother_permanent_address', $user->mother_permanent_address) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="pf-label">Educational Qualifications</label>
                            <input type="text" name="mother_education" class="form-control" placeholder="e.g. BA, Eden College, 1990" value="{{ old('mother_education', $user->mother_education) }}">
                        </div>

                        <div class="col-12">
                            <div class="pf-sub__label" style="margin-top:4px">Occupation</div>
                        </div>

                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">Position</label>
                            <input type="text" name="mother_occupation_position" class="form-control" value="{{ old('mother_occupation_position', $user->mother_occupation_position) }}">
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">Company / Organisation</label>
                            <input type="text" name="mother_occupation_company" class="form-control" value="{{ old('mother_occupation_company', $user->mother_occupation_company) }}">
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">Company Address</label>
                            <input type="text" name="mother_occupation_address" class="form-control" value="{{ old('mother_occupation_address', $user->mother_occupation_address) }}">
                        </div>
                        <div class="col-md-4 col-sm-6 d-flex align-items-end">
                            <div>
                                <label class="pf-label">Expiry Reminder</label>
                                <div class="pf-check">
                                    <input type="checkbox" name="mother_reminder" value="1" id="mother_reminder"
                                        {{ old('mother_reminder', $user->mother_reminder) ? 'checked' : '' }}>
                                    <label for="mother_reminder">Enable expiry reminder</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─────────────────────────────────────────
                 SECTION 7 — Spouse
            ───────────────────────────────────────── --}}
            @php
            $spouseData = is_array($user->spouse_info) ? $user->spouse_info : (json_decode($user->spouse_info, true) ?? []);
            $childrenData = is_array($user->children_info) ? $user->children_info : (json_decode($user->children_info, true) ?? []);
            $currentMarital = old('marital_status', $user->marital_status);
            $currentNoSpouse = old('no_of_spouse', $user->no_of_spouse ?? max(1, count($spouseData)));
            $currentNoChild = old('no_of_children', $user->no_of_children ?? count($childrenData));
            @endphp

            <div class="pf-section" id="sec-spouse" style="{{ $currentMarital == 'married' ? '' : 'display:none' }}">
                <div class="pf-section__head">
                    <span class="pf-section__num">7</span>
                    <h5 class="pf-section__title">Spouse Information</h5>
                </div>
                <div class="pf-section__body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-3 col-sm-5">
                            <label class="pf-label">Number of Spouse(s)</label>
                            <input type="number" name="no_of_spouse" id="no_of_spouse" class="form-control"
                                min="0" max="4" value="{{ $currentNoSpouse }}" oninput="renderSpouseCards()">
                        </div>
                    </div>
                    <div id="spouse_cards"></div>
                </div>
            </div>

            {{-- ─────────────────────────────────────────
                 SECTION 8 — Children
            ───────────────────────────────────────── --}}
            <div class="pf-section" id="sec-children">
                <div class="pf-section__head">
                    <span class="pf-section__num">8</span>
                    <h5 class="pf-section__title">Children Information</h5>
                </div>
                <div class="pf-section__body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-3 col-sm-5">
                            <label class="pf-label">Number of Children</label>
                            <input type="number" name="no_of_children" id="no_of_children" class="form-control"
                                min="0" value="{{ $currentNoChild }}" oninput="renderChildCards()">
                        </div>
                    </div>
                    <div id="child_cards">
                        <p class="pf-empty-msg">Set number of children above to enter their details.</p>
                    </div>
                </div>
            </div>

            {{-- ─────────────────────────────────────────
                 SECTION 9 — Vehicle
            ───────────────────────────────────────── --}}
            <div class="pf-section" id="sec-vehicle">
                <div class="pf-section__head">
                    <span class="pf-section__num">9</span>
                    <h5 class="pf-section__title">Vehicle Information</h5>
                </div>
                <div class="pf-section__body">
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-5">
                            <label class="pf-label">Number of Cars</label>
                            <input type="number" name="no_of_cars" class="form-control" min="0"
                                value="{{ old('no_of_cars', $user->no_of_cars ?? 0) }}">
                        </div>
                        <div class="col-md-5 col-sm-7">
                            <label class="pf-label">Car Details</label>
                            <input type="text" name="car_details" class="form-control" placeholder="Model, Colour, Registration Plate"
                                value="{{ old('car_details', $user->car_details) }}">
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="pf-label">Driver Name & Contact</label>
                            <input type="text" name="driver_details" class="form-control" placeholder="Driver name — 017XXXXXXXX"
                                value="{{ old('driver_details', $user->driver_details) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="pf-label">Vehicle Document</label>
                            <div class="pf-file" onclick="document.getElementById('f_car_doc').click()">
                                <div class="pf-file__icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                                    </svg>
                                </div>
                                <div class="pf-file__text">
                                    <div class="pf-file__cta">Attach document</div>
                                    <div class="pf-file__hint">Registration / Blue Book (PDF/Image)</div>
                                    @if($user->car_details_document)
                                    <div class="pf-file__existing">&#10003; Current file uploaded</div>
                                    @endif
                                    <div class="pf-file__name" id="f_car_doc_name"></div>
                                </div>
                            </div>
                            <input type="file" id="f_car_doc" name="car_details_document" accept=".pdf,.jpg,.jpeg,.png" class="d-none" onchange="pfFile(this,'f_car_doc_name')">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit bar --}}
            <div class="pf-submit">
                <span class="pf-submit__note">Leave file fields empty to keep existing files.</span>
                <button type="submit" class="btn-pf-save">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Update Profile
                </button>
            </div>

        </div>{{-- /.pf-body --}}
    </form>
</div>
@endsection

@push('scripts')
{{-- Pass existing spouse/children data from PHP to JS --}}
<script>
    const _existingSpouses = @json($spouseData);
    const _existingChildren = @json($childrenData);
</script>

<script>
    /* ── File box ──────────────────────────────────────────────────────── */
    function pfFile(input, nameId) {
        const el = document.getElementById(nameId);
        if (input.files.length) {
            el.textContent = input.files[0].name;
            el.classList.add('show');
        } else {
            el.textContent = '';
            el.classList.remove('show');
        }
    }

    /* ── Education rows ────────────────────────────────────────────────── */
    function addEduRow() {
        document.getElementById('edu_rows').insertAdjacentHTML('beforeend', `
        <div class="pf-edu-row">
            <input type="text"   name="edu_exam[]"      class="form-control" placeholder="Degree / Exam">
            <input type="text"   name="edu_institute[]" class="form-control" placeholder="Institute / University">
            <input type="date"   name="edu_date[]"      class="form-control" title="Passing Date">
            <input type="number" name="edu_year[]"      class="form-control" placeholder="Year" min="1970" max="2099">
            <button type="button" class="btn-pf-del" onclick="removeEduRow(this)" title="Remove">✕</button>
        </div>`);
    }

    function removeEduRow(btn) {
        const rows = document.querySelectorAll('.pf-edu-row');
        if (rows.length > 1) btn.closest('.pf-edu-row').remove();
    }

    /* ── Marital status ────────────────────────────────────────────────── */
    document.getElementById('marital_status_sel').addEventListener('change', function() {
        const sec = document.getElementById('sec-spouse');
        if (this.value === 'married') {
            sec.style.display = '';
            renderSpouseCards();
        } else sec.style.display = 'none';
    });

    /* ── Mother status ─────────────────────────────────────────────────── */
    document.getElementById('mother_status_sel').addEventListener('change', function() {
        document.getElementById('mother_expired_wrap').style.display = this.value === 'expired' ? '' : 'none';
    });

    /* ── Blood group options ───────────────────────────────────────────── */
    const _bg = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'].map(b => `<option value="${b}">${b}</option>`).join('');

    /* ── Spouse cards (pre-fill from existing data) ────────────────────── */
    function renderSpouseCards() {
        const n = parseInt(document.getElementById('no_of_spouse').value) || 0;
        const wrap = document.getElementById('spouse_cards');
        wrap.innerHTML = '';
        for (let i = 0; i < n; i++) {
            const sp = _existingSpouses[i] || {};
            wrap.insertAdjacentHTML('beforeend', `
        <div class="pf-subcard">
            <div class="pf-subcard__head">Spouse ${i + 1}</div>
            <div class="row g-3">
                <div class="col-md-4 col-sm-6">
                    <label class="pf-label">Full Name</label>
                    <input type="text" name="spouse_name[]" class="form-control" value="${escHtml(sp.name || '')}">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="pf-label">Date of Birth</label>
                    <input type="date" name="spouse_dob[]" class="form-control" value="${escHtml(sp.dob || '')}">
                </div>
                <div class="col-md-2 col-sm-4">
                    <label class="pf-label">Status</label>
                    <select name="spouse_status[]" class="form-select">
                        <option value="">— Select —</option>
                        <option value="alive"   ${sp.status === 'alive'   ? 'selected' : ''}>Alive</option>
                        <option value="expired" ${sp.status === 'expired' ? 'selected' : ''}>Expired</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-8">
                    <label class="pf-label">Educational Qualifications</label>
                    <input type="text" name="spouse_education[]" class="form-control" placeholder="Degree, Institute" value="${escHtml(sp.education || '')}">
                </div>
            </div>
        </div>`);
        }
    }

    /* ── Children cards (pre-fill from existing data) ──────────────────── */
    function renderChildCards() {
        const n = parseInt(document.getElementById('no_of_children').value) || 0;
        const wrap = document.getElementById('child_cards');
        wrap.innerHTML = '';
        if (!n) {
            wrap.innerHTML = '<p class="pf-empty-msg">Set number of children above to enter their details.</p>';
            return;
        }
        for (let i = 0; i < n; i++) {
            const ch = _existingChildren[i] || {};
            wrap.insertAdjacentHTML('beforeend', `
        <div class="pf-subcard">
            <div class="pf-subcard__head">Child ${i + 1}</div>
            <div class="row g-3">
                <div class="col-md-4 col-sm-6"><label class="pf-label">Full Name</label>
                    <input type="text" name="child_name[]" class="form-control" value="${escHtml(ch.name || '')}"></div>
                <div class="col-md-2 col-sm-4"><label class="pf-label">Gender</label>
                    <select name="child_gender[]" class="form-select">
                        <option value="">— Select —</option>
                        <option value="girl" ${ch.gender === 'girl' ? 'selected' : ''}>Girl</option>
                        <option value="boy"  ${ch.gender === 'boy'  ? 'selected' : ''}>Boy</option>
                    </select></div>
                <div class="col-md-3 col-sm-6"><label class="pf-label">Date of Birth</label>
                    <input type="date" name="child_dob[]" class="form-control" value="${escHtml(ch.dob || '')}"></div>
                <div class="col-md-3 col-sm-4"><label class="pf-label">Blood Group</label>
                    <select name="child_blood_group[]" class="form-select">
                        <option value="">— Select —</option>${_bg.replace(`value="${escHtml(ch.blood_group || '')}"`, `value="${escHtml(ch.blood_group || '')}" selected`)}
                    </select></div>
                <div class="col-md-4 col-sm-6"><label class="pf-label">Birth Certificate No.</label>
                    <input type="text" name="child_birth_certificate[]" class="form-control" value="${escHtml(ch.birth_certificate || '')}"></div>
                <div class="col-md-4 col-sm-6"><label class="pf-label">NID Number</label>
                    <input type="text" name="child_nid[]" class="form-control" value="${escHtml(ch.nid || '')}"></div>
                <div class="col-md-4 col-sm-6"><label class="pf-label">Contact Number</label>
                    <input type="text" name="child_contact[]" class="form-control" value="${escHtml(ch.contact || '')}"></div>
                <div class="col-md-4 col-sm-6"><label class="pf-label">Email Address</label>
                    <input type="email" name="child_email[]" class="form-control" value="${escHtml(ch.email || '')}"></div>
                <div class="col-md-4 col-sm-6"><label class="pf-label">Present Address</label>
                    <input type="text" name="child_present_address[]" class="form-control" value="${escHtml(ch.present_address || '')}"></div>
                <div class="col-md-4 col-sm-6"><label class="pf-label">Permanent Address</label>
                    <input type="text" name="child_permanent_address[]" class="form-control" value="${escHtml(ch.permanent_address || '')}"></div>
                <div class="col-md-6"><label class="pf-label">Educational Qualifications</label>
                    <input type="text" name="child_education[]" class="form-control" placeholder="Degree, Institute, Year, Board" value="${escHtml(ch.education || '')}"></div>
            </div>
        </div>`);
        }
    }

    /* ── XSS-safe value injection ──────────────────────────────────────── */
    function escHtml(str) {
        return String(str).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    /* ── Init on page load ─────────────────────────────────────────────── */
    renderSpouseCards();
    renderChildCards();
</script>
@endpush