<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    /*
    |--------------------------------------------------------------------------
    | Delete Admin
    |--------------------------------------------------------------------------
    */

    public function deleteAdmin($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        
        $admin->delete();

        return back()->with('success', 'Admin deleted successfully.');
    }





    public function createSuperAdmin()
    {
        return view('admin.admins_list.create-super_admin');
    }



    public function storeSuperAdmin(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'role'     => 'super_admin',
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'status'   => 'approved',
        ]);

        return redirect()->route('super_admin.admins.list')
            ->with('success', 'Super Admin created successfully');
    }




    public function superAdminsList()
    {
        $superAdmins = User::where('role', 'super_admin')
            ->latest()
            ->get();

        return view('admin.admins_list.super_admin_list', compact('superAdmins'));
    }



    public function deleteSuperAdmin($id)
    {
        $user = User::where('role', 'super_admin')->findOrFail($id);

        // safety: prevent deleting yourself
        if ($user->id == Auth::id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return back()->with('success', 'Super Admin deleted successfully.');
    }
}
