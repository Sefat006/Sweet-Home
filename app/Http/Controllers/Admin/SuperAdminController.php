<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Manager;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $totalAdmins = User::where('role', 'admin')->count();

        $pendingAdmins = User::where('role', 'admin')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $pendingCount = $pendingAdmins->count();

        $approvedAdmins = User::where('role', 'admin')
            ->where('status', 'approved')
            ->count();

        $rejectedAdmins = User::where('role', 'admin')
            ->where('status', 'rejected')
            ->count();

        $totalManagers = class_exists(Manager::class)
            ? Manager::count()
            : 0;

        return view('admin.dashboard.super_admin', compact(
            'totalAdmins',
            'pendingAdmins',
            'pendingCount',
            'approvedAdmins',
            'rejectedAdmins',
            'totalManagers'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Admin List
    |--------------------------------------------------------------------------
    */

    public function adminsList()
    {
        $admins = User::where('role', 'admin')->latest()->get();

        return view('admin.admins_list.index', compact('admins'));
    }

    /*
    |--------------------------------------------------------------------------
    | Change Admin Status
    |--------------------------------------------------------------------------
    */

    public function changeAdminStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,pending,rejected',
        ]);

        $admin = User::where('role', 'admin')->findOrFail($id);

        $admin->status = $request->status;

        if ($request->status === 'approved') {
            $admin->approved_at = now();
        }

        $admin->save();

        return back()->with('success', 'Admin status updated successfully.');
    }
}