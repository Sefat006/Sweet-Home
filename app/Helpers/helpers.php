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


if (!function_exists('generateTenantId')) {
    function generateTenantId(): string
    {
        $last = \App\Models\Tenant::whereNotNull('tenant_id')->orderByDesc('id')->first();

        $number = $last ? ((int) substr($last->tenant_id, 4)) + 1 : 1;
        return 'TNT-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('calcBillRemaining')) {
    /**
     * Recalculate and return the remaining amount for a monthly bill.
     * Pass the total_amount and paid_amount to get the remainder.
     */
    function calcBillRemaining(float $total, float $paid): float
    {
        return max(0, $total - $paid);
    }
}

if (!function_exists('billCollectionStatus')) {
    /**
     * Derive collection_status (paid / partial / due) from total and paid amounts.
     */
    function billCollectionStatus(float $total, float $paid): string
    {
        if ($paid <= 0) {
            return 'due';
        }
        if ($paid >= $total) {
            return 'paid';
        }
        return 'partial';
    }
}

if (!function_exists('flatTotalRent')) {
    /**
     * Calculate total rent from a flat model or array of rent fields.
     * Accepts an \App\Models\Flat instance or an associative array.
     */
    function flatTotalRent(\App\Models\Flat|array $flat): float
    {
        $fields = ['house_rent', 'wasa', 'common_electricity', 'gas', 'utility', 'parking', 'society_bill', 'security', 'other'];

        $total = 0.0;
        foreach ($fields as $field) {
            $total += (float) (is_array($flat) ? ($flat[$field] ?? 0) : $flat->{$field});
        }
        return $total;
    }
}
