<?php
use Illuminate\Support\Facades\Auth;
// app/Helpers/helpers.php

if (!function_exists('generateAdminId')) {
    function generateAdminId(): string
    {
        $last = \App\Models\User::where('role', 'admin')
                    ->whereNotNull('admin_id')
                    ->orderByDesc('id')
                    ->first();

        $number = $last ? ((int) substr($last->admin_id, 4)) + 1 : 1;
        return 'ADM-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('generateManagerId')) {
    function generateManagerId(): string
    {
        $last = \App\Models\Manager::orderByDesc('id')->first();
        $number = $last ? ((int) substr($last->manager_id, 4)) + 1 : 1;
        return 'MGR-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('isSuperAdmin')) {
    function isSuperAdmin(): bool
    {
        return Auth::check() && Auth::user()->role === 'super_admin';
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }
}

if (!function_exists('isApproved')) {
    function isApproved(): bool
    {
        return Auth::check() && Auth::user()->status === 'approved';
    }
}

if (!function_exists('isManager')) {
    function isManager(): bool
    {
        return Auth::guard('manager')->check();
    }
}