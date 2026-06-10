@extends('admin.layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
/* ══ TOKENS ══════════════════════════════════════════════════════════ */
:root {
    --ink:       #0f172a;
    --ink-2:     #334155;
    --ink-3:     #64748b;
    --ink-4:     #94a3b8;
    --surface:   #ffffff;
    --surface-2: #f8fafc;
    --surface-3: #f1f5f9;
    --border:    #e2e8f0;
    --accent:    #1d4ed8;
    --accent-lt: #eff6ff;
    --accent-dk: #1e3a8a;
    --green:     #16a34a;
    --green-lt:  #f0fdf4;
    --amber:     #d97706;
    --amber-lt:  #fffbeb;
    --red:       #dc2626;
    --red-lt:    #fef2f2;
    --r:         12px;
    --r-sm:      8px;
    --sh:        0 1px 3px rgba(0,0,0,.06), 0 4px 12px rgba(0,0,0,.06);
    --sh-lg:     0 4px 24px rgba(0,0,0,.10);
}

/* ══ RESET ═══════════════════════════════════════════════════════════ */
*, *::before, *::after { box-sizing: border-box; }
body { font-family: 'DM Sans', sans-serif; color: var(--ink); }

/* ══ PAGE LAYOUT ══════════════════════════════════════════════════════ */
.sp-wrap { max-width: 1100px; }

/* ══ HERO / HEADER ════════════════════════════════════════════════════ */
.sp-hero {
    background: linear-gradient(135deg, var(--accent-dk) 0%, var(--accent) 60%, #3b82f6 100%);
    border-radius: var(--r);
    padding: 40px 40px 0;
    margin-bottom: 0;
    position: relative;
    overflow: hidden;
    box-shadow: var(--sh-lg);
}
.sp-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.sp-hero__inner {
    display: flex;
    align-items: flex-end;
    gap: 32px;
    position: relative;
    flex-wrap: wrap;
}
.sp-hero__avatar {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    border: 4px solid rgba(255,255,255,.35);
    background: rgba(255,255,255,.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'DM Serif Display', serif;
    font-size: 2.8rem;
    color: #fff;
    flex-shrink: 0;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0,0,0,.2);
    margin-bottom: -24px;
}
.sp-hero__avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.sp-hero__info {
    flex: 1;
    padding-bottom: 28px;
    min-width: 0;
}
.sp-hero__name {
    font-family: 'DM Serif Display', serif;
    font-size: 2rem;
    color: #fff;
    line-height: 1.2;
    margin: 0 0 6px;
}
.sp-hero__sub {
    font-size: .85rem;
    color: rgba(255,255,255,.75);
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    align-items: center;
}
.sp-hero__sub span { display: flex; align-items: center; gap: 6px; }
.sp-hero__badges {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 12px;
}
.sp-badge {
    font-size: .73rem;
    font-weight: 600;
    letter-spacing: .04em;
    padding: 4px 12px;
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,.3);
    color: #fff;
    background: rgba(255,255,255,.15);
    backdrop-filter: blur(4px);
}
.sp-badge--green  { background: rgba(22,163,74,.25); border-color: rgba(22,163,74,.5); }
.sp-badge--amber  { background: rgba(217,119,6,.25);  border-color: rgba(217,119,6,.5); }
.sp-hero__actions {
    padding-bottom: 28px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    align-items: flex-end;
}
.sp-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 20px;
    border-radius: var(--r-sm);
    font-size: .82rem;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all .18s;
    white-space: nowrap;
}
.sp-btn--ghost {
    background: rgba(255,255,255,.15);
    color: #fff;
    border: 1px solid rgba(255,255,255,.3);
    backdrop-filter: blur(4px);
}
.sp-btn--ghost:hover { background: rgba(255,255,255,.28); color: #fff; }
.sp-btn--solid {
    background: #fff;
    color: var(--accent);
}
.sp-btn--solid:hover { background: var(--accent-lt); }

/* ══ TAB NAV ══════════════════════════════════════════════════════════ */
.sp-tabs {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 6px;
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
    box-shadow: var(--sh);
    margin-bottom: 24px;
}
.sp-tab {
    flex: 1;
    min-width: 90px;
    text-align: center;
    padding: 9px 12px;
    border-radius: var(--r-sm);
    font-size: .8rem;
    font-weight: 600;
    color: var(--ink-3);
    cursor: pointer;
    transition: all .15s;
    border: none;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.sp-tab:hover { background: var(--surface-3); color: var(--ink-2); }
.sp-tab.active {
    background: var(--accent);
    color: #fff;
    box-shadow: 0 2px 8px rgba(29,78,216,.3);
}

/* ══ SECTION PANELS ════════════════════════════════════════════════════ */
.sp-panel { display: none; }
.sp-panel.active { display: block; }

/* ══ CARDS ════════════════════════════════════════════════════════════ */
.sp-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--sh);
    margin-bottom: 20px;
    overflow: hidden;
}
.sp-card__head {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 22px;
    border-bottom: 1px solid var(--border);
    background: var(--surface-2);
}
.sp-card__icon {
    width: 34px;
    height: 34px;
    border-radius: var(--r-sm);
    background: var(--accent-lt);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.sp-card__icon svg { width: 16px; height: 16px; stroke: var(--accent); }
.sp-card__title {
    font-size: .9rem;
    font-weight: 700;
    color: var(--ink);
    margin: 0;
    font-family: 'DM Serif Display', serif;
}
.sp-card__body { padding: 22px; }

/* ══ FIELD GRID ════════════════════════════════════════════════════════ */
.sp-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 18px 24px;
}
.sp-grid--2 { grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); }
.sp-field {}
.sp-field__label {
    font-size: .71rem;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--ink-4);
    margin-bottom: 4px;
}
.sp-field__value {
    font-size: .88rem;
    color: var(--ink);
    font-weight: 500;
    line-height: 1.5;
    word-break: break-word;
}
.sp-field__value--empty {
    color: var(--ink-4);
    font-style: italic;
    font-weight: 400;
    font-size: .82rem;
}

/* ══ DIVIDER ══════════════════════════════════════════════════════════ */
.sp-divider {
    font-size: .69rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--ink-4);
    border-bottom: 1px solid var(--border);
    padding-bottom: 8px;
    margin: 22px 0 18px;
}
.sp-divider:first-child { margin-top: 0; }

/* ══ DOCUMENT CHIP ════════════════════════════════════════════════════ */
.sp-doc {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border-radius: var(--r-sm);
    border: 1px solid var(--border);
    background: var(--surface-2);
    font-size: .8rem;
    font-weight: 500;
    color: var(--ink-2);
    text-decoration: none;
    transition: all .15s;
    max-width: 260px;
}
.sp-doc:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-lt); }
.sp-doc__icon {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: var(--accent-lt);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.sp-doc__icon svg { width: 14px; height: 14px; stroke: var(--accent); }
.sp-doc__name {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.sp-doc--none {
    color: var(--ink-4);
    font-style: italic;
    font-weight: 400;
    font-size: .82rem;
    padding: 0;
    background: none;
    border: none;
}

/* ══ STATUS PILL ══════════════════════════════════════════════════════ */
.sp-pill {
    display: inline-block;
    font-size: .73rem;
    font-weight: 600;
    padding: 3px 11px;
    border-radius: 20px;
}
.sp-pill--green  { background: var(--green-lt);  color: var(--green); }
.sp-pill--amber  { background: var(--amber-lt);  color: var(--amber); }
.sp-pill--red    { background: var(--red-lt);    color: var(--red); }
.sp-pill--blue   { background: var(--accent-lt); color: var(--accent); }
.sp-pill--slate  { background: var(--surface-3); color: var(--ink-3); }

/* ══ EDUCATION TABLE ══════════════════════════════════════════════════ */
.sp-edu-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .85rem;
}
.sp-edu-table th {
    text-align: left;
    font-size: .69rem;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--ink-4);
    font-weight: 600;
    padding: 0 14px 10px;
    border-bottom: 1px solid var(--border);
}
.sp-edu-table th:first-child { padding-left: 0; }
.sp-edu-table td {
    padding: 12px 14px;
    color: var(--ink-2);
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
    font-weight: 500;
}
.sp-edu-table td:first-child { padding-left: 0; color: var(--ink); }
.sp-edu-table tr:last-child td { border-bottom: none; }

/* ══ SUB-PERSON CARD (spouse / child) ════════════════════════════════ */
.sp-person {
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: var(--r-sm);
    padding: 18px;
    margin-bottom: 14px;
}
.sp-person:last-child { margin-bottom: 0; }
.sp-person__head {
    font-size: .78rem;
    font-weight: 700;
    color: var(--accent);
    letter-spacing: .04em;
    text-transform: uppercase;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.sp-person__head::after { content: ''; flex: 1; height: 1px; background: var(--border); }

/* ══ STAT CARDS ════════════════════════════════════════════════════════ */
.sp-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
}
.sp-stat {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 18px;
    text-align: center;
    box-shadow: var(--sh);
    transition: transform .15s, box-shadow .15s;
}
.sp-stat:hover { transform: translateY(-2px); box-shadow: var(--sh-lg); }
.sp-stat__n {
    font-family: 'DM Serif Display', serif;
    font-size: 2rem;
    color: var(--accent);
    line-height: 1;
    margin-bottom: 4px;
}
.sp-stat__label {
    font-size: .74rem;
    color: var(--ink-3);
    font-weight: 500;
}

/* ══ RESPONSIVE ═══════════════════════════════════════════════════════ */
@media (max-width: 768px) {
    .sp-hero { padding: 28px 22px 0; }
    .sp-hero__name { font-size: 1.5rem; }
    .sp-hero__avatar { width: 80px; height: 80px; font-size: 2rem; }
    .sp-grid { grid-template-columns: 1fr 1fr; }
    .sp-stats { grid-template-columns: repeat(2, 1fr); }
    .sp-hero__actions { flex-direction: row; align-items: center; padding-bottom: 22px; }
}
@media (max-width: 480px) {
    .sp-grid { grid-template-columns: 1fr; }
}

/* ══ ANIMATION ════════════════════════════════════════════════════════ */
@keyframes fadein {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}
.sp-panel.active { animation: fadein .22s ease; }
</style>
@endpush

@section('content')
@php
    $educationData = is_array($user->education) ? $user->education : (json_decode($user->education, true) ?? []);
    $spouseData    = is_array($user->spouse_info) ? $user->spouse_info : (json_decode($user->spouse_info, true) ?? []);
    $childrenData  = is_array($user->children_info) ? $user->children_info : (json_decode($user->children_info, true) ?? []);

    function spVal($v, $fallback = '—') {
        return (isset($v) && $v !== '' && $v !== null) ? $v : $fallback;
    }

    $statusMap = [
        'single'   => ['label' => 'Single',   'class' => 'sp-pill--slate'],
        'married'  => ['label' => 'Married',  'class' => 'sp-pill--green'],
        'divorced' => ['label' => 'Divorced', 'class' => 'sp-pill--amber'],
        'widowed'  => ['label' => 'Widowed',  'class' => 'sp-pill--slate'],
    ];
    $bgColors = ['A+'=>'#ef4444','A-'=>'#f87171','B+'=>'#3b82f6','B-'=>'#60a5fa','O+'=>'#10b981','O-'=>'#34d399','AB+'=>'#8b5cf6','AB-'=>'#a78bfa'];
@endphp

<div class="container-fluid">

    {{-- Breadcrumb --}}
    <!-- <div class="breadcrumb__content mb-4">
        <div class="breadcrumb__content__left">
            <div class="breadcrumb__title"><h2>Profile</h2></div>
        </div>
        <div class="breadcrumb__content__right">
            <a href="{{ route('admin.profile.edit') }}" class="btn btn-sm btn-primary">
                Edit Profile
            </a>
        </div>
    </div> -->

    <div class="sp-wrap">

        {{-- ══ HERO ══════════════════════════════════════════════════════ --}}
        <div class="sp-hero mb-4">
            <div class="sp-hero__inner">
                <div class="sp-hero__avatar">
                    @if($user->image)
                        <img src="{{ asset($user->image) }}" alt="{{ $user->name }}">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <div class="sp-hero__info">
                    <h1 class="sp-hero__name">{{ $user->name }}</h1>
                    <div class="sp-hero__sub">
                        <span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            {{ $user->email }}
                        </span>
                        <span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                            {{ $user->phone }}
                        </span>
                        @php
                            $occupations = is_array($user->occupation_info) ? $user->occupation_info : (json_decode($user->occupation_info, true) ?? []);
                            $primaryOcc = null;
                            if (count($occupations) > 0) {
                                $firstOcc = $occupations[0];
                                if (($firstOcc['type'] ?? 'job') === 'job') {
                                    $primaryOcc = (!empty($firstOcc['company']) ? $firstOcc['company'] : null);
                                } else {
                                    $primaryOcc = (!empty($firstOcc['business_name']) ? $firstOcc['business_name'] : null);
                                }
                            }
                        @endphp
                        @if($primaryOcc)
                        <span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            {{ $primaryOcc }}
                        </span>
                        @elseif($user->occupation_position)
                        <span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            {{ $user->occupation_position }}@if($user->occupation_company), {{ $user->occupation_company }}@endif
                        </span>
                        @endif
                    </div>
                    <div class="sp-hero__badges">
                        <span class="sp-badge">{{ strtoupper($user->admin_id ?? 'ADMIN') }}</span>
                        @if($user->marital_status)
                            <span class="sp-badge">{{ ucfirst($user->marital_status) }}</span>
                        @endif
                        @if($user->blood_group)
                            <span class="sp-badge">{{ $user->blood_group }}</span>
                        @endif
                        @if($user->profile_completed)
                            <span class="sp-badge sp-badge--green">Profile Complete</span>
                        @endif
                    </div>
                </div>
                <div class="sp-hero__actions">
                    <a href="{{ route('admin.profile.edit') }}" class="sp-btn sp-btn--solid">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>

        {{-- ══ QUICK STATS ════════════════════════════════════════════════ --}}
        <div class="sp-stats">
            <div class="sp-stat">
                <div class="sp-stat__n">{{ $user->no_of_children ?? 0 }}</div>
                <div class="sp-stat__label">Children</div>
            </div>
            <div class="sp-stat">
                <div class="sp-stat__n">{{ $user->no_of_spouse ?? 0 }}</div>
                <div class="sp-stat__label">Spouse(s)</div>
            </div>
            <div class="sp-stat">
                <div class="sp-stat__n">{{ $user->no_of_cars ?? 0 }}</div>
                <div class="sp-stat__label">Cars</div>
            </div>
            <div class="sp-stat">
                <div class="sp-stat__n">{{ count($educationData) }}</div>
                <div class="sp-stat__label">Qualifications</div>
            </div>
        </div>

        {{-- ══ TAB NAVIGATION ══════════════════════════════════════════════ --}}
        <div class="sp-tabs" id="sp-tabs">
            <button class="sp-tab active" onclick="switchTab('personal', this)">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                Personal
            </button>
            <button class="sp-tab" onclick="switchTab('documents', this)">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/></svg>
                Documents
            </button>
            <button class="sp-tab" onclick="switchTab('education', this)">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
                Education
            </button>
            <button class="sp-tab" onclick="switchTab('family', this)">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                Family
            </button>
            <button class="sp-tab" onclick="switchTab('vehicle', this)">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                Vehicle
            </button>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             TAB 1 — PERSONAL
        ══════════════════════════════════════════════════════════════ --}}
        <div class="sp-panel active" id="tab-personal">

            {{-- Basic Info --}}
            <div class="sp-card">
                <div class="sp-card__head">
                    <div class="sp-card__icon">
                        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    </div>
                    <h5 class="sp-card__title">Personal Details</h5>
                </div>
                <div class="sp-card__body">
                    <div class="sp-grid">
                        <div class="sp-field">
                            <div class="sp-field__label">Full Name</div>
                            <div class="sp-field__value">{{ $user->name }}</div>
                        </div>
                        <div class="sp-field">
                            <div class="sp-field__label">Email Address</div>
                            <div class="sp-field__value">{{ $user->email }}</div>
                        </div>
                        <div class="sp-field">
                            <div class="sp-field__label">Phone Number</div>
                            <div class="sp-field__value">{{ $user->phone }}</div>
                        </div>
                        <div class="sp-field">
                            <div class="sp-field__label">Date of Birth</div>
                            <div class="sp-field__value">
                                @if($user->date_of_birth)
                                    {{ \Carbon\Carbon::parse($user->date_of_birth)->format('d M Y') }}
                                    <span style="font-size:.78rem;color:var(--ink-4);margin-left:6px">({{ \Carbon\Carbon::parse($user->date_of_birth)->age }} yrs)</span>
                                @else
                                    <span class="sp-field__value--empty">Not provided</span>
                                @endif
                            </div>
                        </div>
                        <div class="sp-field">
                            <div class="sp-field__label">Blood Group</div>
                            <div class="sp-field__value">
                                @if($user->blood_group)
                                    <span style="display:inline-flex;align-items:center;gap:6px">
                                        <span style="width:10px;height:10px;border-radius:50%;background:{{ $bgColors[$user->blood_group] ?? '#94a3b8' }};flex-shrink:0"></span>
                                        {{ $user->blood_group }}
                                    </span>
                                @else
                                    <span class="sp-field__value--empty">Not provided</span>
                                @endif
                            </div>
                        </div>
                        <div class="sp-field">
                            <div class="sp-field__label">Marital Status</div>
                            <div class="sp-field__value">
                                @if($user->marital_status)
                                    @php $ms = $statusMap[$user->marital_status] ?? null; @endphp
                                    @if($ms)
                                        <span class="sp-pill {{ $ms['class'] }}">{{ $ms['label'] }}</span>
                                    @else
                                        {{ ucfirst($user->marital_status) }}
                                    @endif
                                @else
                                    <span class="sp-field__value--empty">Not provided</span>
                                @endif
                            </div>
                        </div>
                        <div class="sp-field">
                            <div class="sp-field__label">Emergency Contact</div>
                            <div class="sp-field__value">
                                @if($user->emergency_contact)
                                    {{ $user->emergency_contact }}
                                @else
                                    <span class="sp-field__value--empty">Not provided</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="sp-divider">Address</div>
                    <div class="sp-grid sp-grid--2">
                        <div class="sp-field">
                            <div class="sp-field__label">Present Address</div>
                            <div class="sp-field__value">
                                @if($user->present_address)
                                    {{ $user->present_address }}
                                @else
                                    <span class="sp-field__value--empty">Not provided</span>
                                @endif
                            </div>
                        </div>
                        <div class="sp-field">
                            <div class="sp-field__label">Permanent Address</div>
                            <div class="sp-field__value">
                                @if($user->permanent_address)
                                    {{ $user->permanent_address }}
                                @else
                                    <span class="sp-field__value--empty">Not provided</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Occupation --}}
            <div class="sp-card">
                <div class="sp-card__head">
                    <div class="sp-card__icon">
                        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                    </div>
                    <h5 class="sp-card__title">Occupation</h5>
                </div>
                <div class="sp-card__body">
                    @php
                        $occupations = is_array($user->occupation_info) ? $user->occupation_info : (json_decode($user->occupation_info, true) ?? []);
                    @endphp

                    @if(count($occupations) > 0)
                        @foreach($occupations as $index => $occ)
                            <div class="sp-person" style="margin-top: {{ $index > 0 ? '16px' : '0' }}">
                                <div class="sp-person__head">Occupation {{ $index + 1 }} ({{ ucfirst($occ['type'] ?? 'Job') }})</div>
                                <div class="sp-grid">
                                    @if(($occ['type'] ?? 'job') === 'job')
                                        <div class="sp-field">
                                            <div class="sp-field__label">Company Name</div>
                                            <div class="sp-field__value">{{ spVal($occ['company'] ?? null) }}</div>
                                        </div>
                                        <div class="sp-field">
                                            <div class="sp-field__label">Address</div>
                                            <div class="sp-field__value">{{ spVal($occ['address'] ?? null) }}</div>
                                        </div>
                                        <div class="sp-field">
                                            <div class="sp-field__label">Verification Documents</div>
                                            <div class="sp-field__value">
                                                @if(!empty($occ['documents']))
                                                    @foreach($occ['documents'] as $docIndex => $doc)
                                                        @if($doc)
                                                            <a href="{{ asset($doc) }}" target="_blank" class="sp-doc mb-1 d-inline-flex">
                                                                 <span class="sp-doc__icon">
                                                                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32"/></svg>
                                                                </span>
                                                                <span class="sp-doc__name">Doc {{ $docIndex + 1 }}</span>
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span class="sp-doc--none">No document uploaded</span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="sp-field">
                                            <div class="sp-field__label">Business Name</div>
                                            <div class="sp-field__value">{{ spVal($occ['business_name'] ?? null) }}</div>
                                        </div>
                                        <div class="sp-field">
                                            <div class="sp-field__label">Business Address</div>
                                            <div class="sp-field__value">{{ spVal($occ['business_address'] ?? null) }}</div>
                                        </div>
                                        <div class="sp-field">
                                            <div class="sp-field__label">Trade License Document</div>
                                            <div class="sp-field__value">
                                                @if(!empty($occ['trade_docs']))
                                                    @foreach($occ['trade_docs'] as $docIndex => $doc)
                                                        @if($doc)
                                                            <a href="{{ asset($doc) }}" target="_blank" class="sp-doc mb-1 d-inline-flex">
                                                                <span class="sp-doc__icon">
                                                                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32"/></svg>
                                                                </span>
                                                                <span class="sp-doc__name">Doc {{ $docIndex + 1 }}</span>
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span class="sp-doc--none">No document uploaded</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="sp-field">
                                            <div class="sp-field__label">TIN Certificate</div>
                                            <div class="sp-field__value">
                                                @if(!empty($occ['tin_docs']))
                                                    @foreach($occ['tin_docs'] as $docIndex => $doc)
                                                        @if($doc)
                                                            <a href="{{ asset($doc) }}" target="_blank" class="sp-doc mb-1 d-inline-flex">
                                                                <span class="sp-doc__icon">
                                                                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32"/></svg>
                                                                </span>
                                                                <span class="sp-doc__name">Doc {{ $docIndex + 1 }}</span>
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span class="sp-doc--none">No document uploaded</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="sp-field">
                                            <div class="sp-field__label">Other Documents</div>
                                            <div class="sp-field__value">
                                                @if(!empty($occ['other_docs']))
                                                    @foreach($occ['other_docs'] as $docIndex => $doc)
                                                        @if($doc)
                                                            <a href="{{ asset($doc) }}" target="_blank" class="sp-doc mb-1 d-inline-flex">
                                                                <span class="sp-doc__icon">
                                                                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32"/></svg>
                                                                </span>
                                                                <span class="sp-doc__name">Doc {{ $docIndex + 1 }}</span>
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span class="sp-doc--none">No document uploaded</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="sp-grid">
                            <div class="sp-field">
                                <div class="sp-field__label">Designation / Position</div>
                                <div class="sp-field__value">
                                    @if($user->occupation_position)
                                        {{ $user->occupation_position }}
                                    @else
                                        <span class="sp-field__value--empty">Not provided</span>
                                    @endif
                                </div>
                            </div>
                            <div class="sp-field">
                                <div class="sp-field__label">Company / Organisation</div>
                                <div class="sp-field__value">
                                    @if($user->occupation_company)
                                        {{ $user->occupation_company }}
                                    @else
                                        <span class="sp-field__value--empty">Not provided</span>
                                    @endif
                                </div>
                            </div>
                            <div class="sp-field">
                                <div class="sp-field__label">Company Address</div>
                                <div class="sp-field__value">
                                    @if($user->occupation_address)
                                        {{ $user->occupation_address }}
                                    @else
                                        <span class="sp-field__value--empty">Not provided</span>
                                    @endif
                                </div>
                            </div>
                            <div class="sp-field">
                                <div class="sp-field__label">Occupation Document</div>
                                <div class="sp-field__value">
                                    @if($user->occupation_document)
                                        <a href="{{ asset($user->occupation_document) }}" target="_blank" class="sp-doc">
                                            <span class="sp-doc__icon">
                                                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32"/></svg>
                                            </span>
                                            <span class="sp-doc__name">{{ basename($user->occupation_document) }}</span>
                                        </a>
                                    @else
                                        <span class="sp-doc--none">No document uploaded</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>{{-- /#tab-personal --}}

        {{-- ══════════════════════════════════════════════════════════════
             TAB 2 — DOCUMENTS
        ══════════════════════════════════════════════════════════════ --}}
        <div class="sp-panel" id="tab-documents">

            @php
            $docSections = [
                ['label' => 'National ID (NID)', 'num_field' => 'nid_number', 'num_label' => 'NID Number', 'doc_field' => 'nid_document', 'expiry_field' => null, 'expiry_label' => null],
                ['label' => 'Passport', 'num_field' => 'passport_number', 'num_label' => 'Passport Number', 'doc_field' => 'passport_document', 'expiry_field' => 'passport_expiry', 'expiry_label' => 'Valid Until'],
                ['label' => 'Tax Identification (TIN)', 'num_field' => 'tin_number', 'num_label' => 'TIN Number', 'doc_field' => 'tin_document', 'expiry_field' => null, 'expiry_label' => null],
                ['label' => 'Driving Licence', 'num_field' => 'driving_licence_number', 'num_label' => 'Licence Number', 'doc_field' => 'driving_licence_document', 'expiry_field' => 'driving_licence_expiry', 'expiry_label' => 'Valid Until'],
            ];
            @endphp

            @foreach($docSections as $sec)
            <div class="sp-card">
                <div class="sp-card__head">
                    <div class="sp-card__icon">
                        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/></svg>
                    </div>
                    <h5 class="sp-card__title">{{ $sec['label'] }}</h5>
                </div>
                <div class="sp-card__body">
                    <div class="sp-grid">
                        <div class="sp-field">
                            <div class="sp-field__label">{{ $sec['num_label'] }}</div>
                            <div class="sp-field__value">
                                @if($user->{$sec['num_field']})
                                    <code style="background:var(--surface-3);padding:3px 9px;border-radius:5px;font-size:.85rem;letter-spacing:.04em">{{ $user->{$sec['num_field']} }}</code>
                                @else
                                    <span class="sp-field__value--empty">Not provided</span>
                                @endif
                            </div>
                        </div>
                        @if($sec['expiry_field'])
                        <div class="sp-field">
                            <div class="sp-field__label">{{ $sec['expiry_label'] }}</div>
                            <div class="sp-field__value">
                                @if($user->{$sec['expiry_field']})
                                    @php
                                        $expDate = \Carbon\Carbon::parse($user->{$sec['expiry_field']});
                                        $expired = $expDate->isPast();
                                        $soonExp = !$expired && $expDate->diffInDays(now()) < 90;
                                    @endphp
                                    <span style="display:flex;align-items:center;gap:8px">
                                        {{ $expDate->format('d M Y') }}
                                        @if($expired)
                                            <span class="sp-pill sp-pill--red">Expired</span>
                                        @elseif($soonExp)
                                            <span class="sp-pill sp-pill--amber">Expiring soon</span>
                                        @else
                                            <span class="sp-pill sp-pill--green">Valid</span>
                                        @endif
                                    </span>
                                @else
                                    <span class="sp-field__value--empty">Not provided</span>
                                @endif
                            </div>
                        </div>
                        @endif
                        <div class="sp-field">
                            <div class="sp-field__label">Document File</div>
                            <div class="sp-field__value">
                                @if($user->{$sec['doc_field']})
                                    <a href="{{ asset($user->{$sec['doc_field']}) }}" target="_blank" class="sp-doc">
                                        <span class="sp-doc__icon">
                                            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32"/></svg>
                                        </span>
                                        <span class="sp-doc__name">{{ basename($user->{$sec['doc_field']}) }}</span>
                                    </a>
                                @else
                                    <span class="sp-doc--none">No document uploaded</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>{{-- /#tab-documents --}}

        {{-- ══════════════════════════════════════════════════════════════
             TAB 3 — EDUCATION
        ══════════════════════════════════════════════════════════════ --}}
        <div class="sp-panel" id="tab-education">
            <div class="sp-card">
                <div class="sp-card__head">
                    <div class="sp-card__icon">
                        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
                    </div>
                    <h5 class="sp-card__title">Educational Qualifications</h5>
                </div>
                <div class="sp-card__body">
                    @if(count($educationData) > 0 && !empty(array_filter(array_column($educationData, 'exam'))))
                    <div style="overflow-x:auto">
                        <table class="sp-edu-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Degree / Exam</th>
                                    <th>Institute / University</th>
                                    <th>Year</th>
                                    <th>Documents</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($educationData as $i => $edu)
                                @if(!empty($edu['exam']) || !empty($edu['institute']))
                                <tr>
                                    <td style="color:var(--ink-4);width:36px">{{ $i + 1 }}</td>
                                    <td>
                                        <span style="font-weight:600;color:var(--ink)">{{ $edu['exam'] ?? '—' }}</span>
                                    </td>
                                    <td>{{ $edu['institute'] ?? '—' }}</td>
                                    <td>
                                        @if(!empty($edu['year']))
                                            <span class="sp-pill sp-pill--blue">{{ $edu['year'] }}</span>
                                        @else —
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($edu['documents']) && is_array($edu['documents']))
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($edu['documents'] as $docIndex => $doc)
                                                    @if($doc)
                                                        <a href="{{ asset($doc) }}" target="_blank" class="sp-doc mb-1 d-inline-flex">
                                                            <span class="sp-doc__icon">
                                                                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32"/></svg>
                                                            </span>
                                                            <span class="sp-doc__name">Doc {{ $docIndex + 1 }}</span>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="sp-doc--none">No docs</span>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    @if($user->education_document)
                    <div class="sp-divider">Legacy Education Document</div>
                    <div class="sp-field">
                        <div class="sp-field__label">Certificate / Transcript</div>
                        <div class="sp-field__value">
                            <a href="{{ asset($user->education_document) }}" target="_blank" class="sp-doc">
                                <span class="sp-doc__icon">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32"/></svg>
                                </span>
                                <span class="sp-doc__name">{{ basename($user->education_document) }}</span>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>{{-- /#tab-education --}}

        {{-- ══════════════════════════════════════════════════════════════
             TAB 4 — FAMILY
        ══════════════════════════════════════════════════════════════ --}}
        <div class="sp-panel" id="tab-family">

            {{-- Father --}}
            <div class="sp-card">
                <div class="sp-card__head">
                    <div class="sp-card__icon" style="background:#fff7ed">
                        <svg fill="none" stroke="#ea580c" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/></svg>
                    </div>
                    <h5 class="sp-card__title">Father's Information</h5>
                    @if($user->father_status)
                        <span class="sp-pill {{ $user->father_status == 'alive' ? 'sp-pill--green' : 'sp-pill--slate' }}" style="margin-left:auto">
                            {{ ucfirst($user->father_status) }}
                        </span>
                    @endif
                </div>
                <div class="sp-card__body">
                    <div class="sp-grid">
                        <div class="sp-field"><div class="sp-field__label">Full Name</div>
                            <div class="sp-field__value">@if($user->father_name) {{ $user->father_name }} @else <span class="sp-field__value--empty">Not provided</span> @endif</div></div>
                        <div class="sp-field"><div class="sp-field__label">Date of Birth</div>
                            <div class="sp-field__value">@if($user->father_dob) {{ \Carbon\Carbon::parse($user->father_dob)->format('d M Y') }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                        <div class="sp-field"><div class="sp-field__label">Blood Group</div>
                            <div class="sp-field__value">@if($user->father_blood_group) {{ $user->father_blood_group }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                        <div class="sp-field"><div class="sp-field__label">Contact Number</div>
                            <div class="sp-field__value">@if($user->father_contact) {{ $user->father_contact }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                        <div class="sp-field"><div class="sp-field__label">Email Address</div>
                            <div class="sp-field__value">@if($user->father_email) {{ $user->father_email }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                        <div class="sp-field"><div class="sp-field__label">NID Number</div>
                            <div class="sp-field__value">@if($user->father_nid_number) <code style="background:var(--surface-3);padding:2px 8px;border-radius:4px;font-size:.83rem">{{ $user->father_nid_number }}</code> @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                        <div class="sp-field"><div class="sp-field__label">Education</div>
                            <div class="sp-field__value">@if($user->father_education) {{ $user->father_education }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                        <div class="sp-field"><div class="sp-field__label">Position</div>
                            <div class="sp-field__value">@if($user->father_occupation_position) {{ $user->father_occupation_position }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                        <div class="sp-field"><div class="sp-field__label">Company</div>
                            <div class="sp-field__value">@if($user->father_occupation_company) {{ $user->father_occupation_company }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                    </div>
                    @if($user->father_present_address || $user->father_permanent_address)
                    <div class="sp-divider">Addresses</div>
                    <div class="sp-grid sp-grid--2">
                        <div class="sp-field"><div class="sp-field__label">Present Address</div>
                            <div class="sp-field__value">@if($user->father_present_address) {{ $user->father_present_address }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                        <div class="sp-field"><div class="sp-field__label">Permanent Address</div>
                            <div class="sp-field__value">@if($user->father_permanent_address) {{ $user->father_permanent_address }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Mother --}}
            <div class="sp-card">
                <div class="sp-card__head">
                    <div class="sp-card__icon" style="background:#fdf4ff">
                        <svg fill="none" stroke="#a21caf" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/></svg>
                    </div>
                    <h5 class="sp-card__title">Mother's Information</h5>
                    @if($user->mother_status)
                        <span class="sp-pill {{ $user->mother_status == 'alive' ? 'sp-pill--green' : 'sp-pill--slate' }}" style="margin-left:auto">
                            {{ ucfirst($user->mother_status) }}
                        </span>
                    @endif
                </div>
                <div class="sp-card__body">
                    <div class="sp-grid">
                        <div class="sp-field"><div class="sp-field__label">Full Name</div>
                            <div class="sp-field__value">@if($user->mother_name) {{ $user->mother_name }} @else <span class="sp-field__value--empty">Not provided</span> @endif</div></div>
                        <div class="sp-field"><div class="sp-field__label">Date of Birth</div>
                            <div class="sp-field__value">@if($user->mother_dob) {{ \Carbon\Carbon::parse($user->mother_dob)->format('d M Y') }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                        <div class="sp-field"><div class="sp-field__label">Blood Group</div>
                            <div class="sp-field__value">@if($user->mother_blood_group) {{ $user->mother_blood_group }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                        <div class="sp-field"><div class="sp-field__label">Contact Number</div>
                            <div class="sp-field__value">@if($user->mother_contact) {{ $user->mother_contact }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                        <div class="sp-field"><div class="sp-field__label">Email Address</div>
                            <div class="sp-field__value">@if($user->mother_email) {{ $user->mother_email }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                        <div class="sp-field"><div class="sp-field__label">NID Number</div>
                            <div class="sp-field__value">@if($user->mother_nid_number) <code style="background:var(--surface-3);padding:2px 8px;border-radius:4px;font-size:.83rem">{{ $user->mother_nid_number }}</code> @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                        @if($user->mother_status == 'expired' && $user->mother_expired_date)
                        <div class="sp-field"><div class="sp-field__label">Date of Death</div>
                            <div class="sp-field__value">{{ \Carbon\Carbon::parse($user->mother_expired_date)->format('d M Y') }}</div></div>
                        @endif
                        <div class="sp-field"><div class="sp-field__label">Education</div>
                            <div class="sp-field__value">@if($user->mother_education) {{ $user->mother_education }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                        <div class="sp-field"><div class="sp-field__label">Position</div>
                            <div class="sp-field__value">@if($user->mother_occupation_position) {{ $user->mother_occupation_position }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                        <div class="sp-field"><div class="sp-field__label">Company</div>
                            <div class="sp-field__value">@if($user->mother_occupation_company) {{ $user->mother_occupation_company }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                    </div>
                </div>
            </div>

            {{-- Spouse --}}
            @if(count($spouseData) > 0)
            <div class="sp-card">
                <div class="sp-card__head">
                    <div class="sp-card__icon" style="background:#fdf2f8">
                        <svg fill="none" stroke="#db2777" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                    </div>
                    <h5 class="sp-card__title">Spouse Information</h5>
                    <span class="sp-pill sp-pill--blue" style="margin-left:auto">{{ count($spouseData) }} Spouse(s)</span>
                </div>
                <div class="sp-card__body">
                    @foreach($spouseData as $i => $sp)
                    <div class="sp-person">
                        <div class="sp-person__head">Spouse {{ $i + 1 }}</div>
                        <div class="sp-grid">
                            <div class="sp-field"><div class="sp-field__label">Full Name</div>
                                <div class="sp-field__value">@if(!empty($sp['name'])) {{ $sp['name'] }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                            <div class="sp-field"><div class="sp-field__label">Date of Birth</div>
                                <div class="sp-field__value">@if(!empty($sp['dob'])) {{ \Carbon\Carbon::parse($sp['dob'])->format('d M Y') }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                            <div class="sp-field"><div class="sp-field__label">Status</div>
                                <div class="sp-field__value">@if(!empty($sp['status'])) <span class="sp-pill {{ $sp['status'] == 'alive' ? 'sp-pill--green' : 'sp-pill--slate' }}">{{ ucfirst($sp['status']) }}</span> @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                            <div class="sp-field"><div class="sp-field__label">Education</div>
                                <div class="sp-field__value">@if(!empty($sp['education'])) {{ $sp['education'] }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Children --}}
            @if(count($childrenData) > 0)
            <div class="sp-card">
                <div class="sp-card__head">
                    <div class="sp-card__icon" style="background:#f0fdf4">
                        <svg fill="none" stroke="#16a34a" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197"/></svg>
                    </div>
                    <h5 class="sp-card__title">Children Information</h5>
                    <span class="sp-pill sp-pill--green" style="margin-left:auto">{{ count($childrenData) }} Child(ren)</span>
                </div>
                <div class="sp-card__body">
                    @foreach($childrenData as $i => $ch)
                    <div class="sp-person">
                        <div class="sp-person__head">
                            Child {{ $i + 1 }}
                            @if(!empty($ch['gender']))
                                <span class="sp-pill {{ $ch['gender'] == 'girl' ? 'sp-pill--red' : 'sp-pill--blue' }}" style="font-size:.68rem">{{ ucfirst($ch['gender']) }}</span>
                            @endif
                        </div>
                        <div class="sp-grid">
                            <div class="sp-field"><div class="sp-field__label">Full Name</div>
                                <div class="sp-field__value">@if(!empty($ch['name'])) {{ $ch['name'] }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                            <div class="sp-field"><div class="sp-field__label">Date of Birth</div>
                                <div class="sp-field__value">@if(!empty($ch['dob'])) {{ \Carbon\Carbon::parse($ch['dob'])->format('d M Y') }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                            <div class="sp-field"><div class="sp-field__label">Blood Group</div>
                                <div class="sp-field__value">@if(!empty($ch['blood_group'])) {{ $ch['blood_group'] }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                            <div class="sp-field"><div class="sp-field__label">Contact</div>
                                <div class="sp-field__value">@if(!empty($ch['contact'])) {{ $ch['contact'] }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                            <div class="sp-field"><div class="sp-field__label">Email</div>
                                <div class="sp-field__value">@if(!empty($ch['email'])) {{ $ch['email'] }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                            <div class="sp-field"><div class="sp-field__label">NID Number</div>
                                <div class="sp-field__value">@if(!empty($ch['nid'])) <code style="background:var(--surface-3);padding:2px 8px;border-radius:4px;font-size:.83rem">{{ $ch['nid'] }}</code> @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                            <div class="sp-field"><div class="sp-field__label">Birth Certificate</div>
                                <div class="sp-field__value">@if(!empty($ch['birth_certificate'])) {{ $ch['birth_certificate'] }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                            <div class="sp-field"><div class="sp-field__label">Education</div>
                                <div class="sp-field__value">@if(!empty($ch['education'])) {{ $ch['education'] }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                        </div>
                        @if(!empty($ch['present_address']) || !empty($ch['permanent_address']))
                        <div class="sp-divider" style="margin-top:14px">Address</div>
                        <div class="sp-grid sp-grid--2">
                            <div class="sp-field"><div class="sp-field__label">Present</div>
                                <div class="sp-field__value">@if(!empty($ch['present_address'])) {{ $ch['present_address'] }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                            <div class="sp-field"><div class="sp-field__label">Permanent</div>
                                <div class="sp-field__value">@if(!empty($ch['permanent_address'])) {{ $ch['permanent_address'] }} @else <span class="sp-field__value--empty">—</span> @endif</div></div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(count($spouseData) == 0 && count($childrenData) == 0 && !$user->father_name && !$user->mother_name)
                <div class="sp-card">
                    <div class="sp-card__body" style="text-align:center;padding:48px">
                        <div style="font-size:2.5rem;margin-bottom:12px">👨‍👩‍👧‍👦</div>
                        <div style="font-size:.9rem;color:var(--ink-3)">No family information has been recorded yet.</div>
                        <a href="{{ route('admin.profile.edit') }}" class="sp-btn sp-btn--solid" style="margin-top:16px;display:inline-flex">Add Family Info</a>
                    </div>
                </div>
            @endif

        </div>{{-- /#tab-family --}}

        {{-- ══════════════════════════════════════════════════════════════
             TAB 5 — VEHICLE
        ══════════════════════════════════════════════════════════════ --}}
        <div class="sp-panel" id="tab-vehicle">
            <div class="sp-card">
                <div class="sp-card__head">
                    <div class="sp-card__icon">
                        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                    </div>
                    <h5 class="sp-card__title">Vehicle Details</h5>
                </div>
                <div class="sp-card__body">
                    <div class="sp-grid">
                        <div class="sp-field">
                            <div class="sp-field__label">Number of Cars</div>
                            <div class="sp-field__value">
                                <span style="font-family:'DM Serif Display',serif;font-size:1.6rem;color:var(--accent)">{{ $user->no_of_cars ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="sp-field">
                            <div class="sp-field__label">Car Details</div>
                            <div class="sp-field__value">
                                @if($user->car_details)
                                    {{ $user->car_details }}
                                @else
                                    <span class="sp-field__value--empty">Not provided</span>
                                @endif
                            </div>
                        </div>
                        <div class="sp-field">
                            <div class="sp-field__label">Driver Name & Contact</div>
                            <div class="sp-field__value">
                                @if($user->driver_details)
                                    {{ $user->driver_details }}
                                @else
                                    <span class="sp-field__value--empty">Not provided</span>
                                @endif
                            </div>
                        </div>
                        <div class="sp-field">
                            <div class="sp-field__label">Vehicle Document</div>
                            <div class="sp-field__value">
                                @if($user->car_details_document)
                                    <a href="{{ asset($user->car_details_document) }}" target="_blank" class="sp-doc">
                                        <span class="sp-doc__icon">
                                            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32"/></svg>
                                        </span>
                                        <span class="sp-doc__name">{{ basename($user->car_details_document) }}</span>
                                    </a>
                                @else
                                    <span class="sp-doc--none">No document uploaded</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>{{-- /#tab-vehicle --}}

    </div>{{-- /.sp-wrap --}}
</div>
@endsection

@push('scripts')
<script>
function switchTab(name, btn) {
    document.querySelectorAll('.sp-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.sp-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-' + name).classList.add('active');
}
</script>
@endpush