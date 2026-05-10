<?php

namespace App\Http\Controllers\Back\Auth;

use App\Http\Controllers\Back\SuperAdmin\SuperAdminController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ─── Show login/register page ─────────────────────────────────────
    public function showAuthPage()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.auth');
    }

    // ─── Register (Admin only) ────────────────────────────────────────
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'role'     => 'admin',
            'admin_id' => generateAdminId(),
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'status'   => 'pending', // SuperAdmin approval দরকার
        ]);

        return redirect()->route('login')
            ->with('success', 'Registration successful! Please wait for admin approval.');
    }

    // ─── Login ────────────────────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {

            $request->session()->regenerate();

            $user = Auth::user();

            // Admin হলে approval check করো
            if (isAdmin() && !isApproved()) {

                Auth::logout();

                return back()->withErrors([
                    'email' => 'Your account is pending approval from Super Admin.',
                ]);
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    // ─── Logout ───────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ─── Dashboard redirect by role ───────────────────────────────────
    public function dashboard()
    {
        $user = Auth::user();

        if (isSuperAdmin()) {
            return app(SuperAdminController::class)
                ->dashboard();
        }

        if (isAdmin()) {
            return view('admin.dashboard.admin');
        }

        if ($user->role === 'manager') {
            return view('manager.dashboard');
        }

        Auth::logout();

        return redirect()->route('login');
    }
}
