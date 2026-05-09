<?php

use App\Http\Controllers\Front\WelcomeController;
use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\SuperAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index']);

// ─── Auth (Guest only) ────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    // We name the GET route 'login' so Laravel's RedirectIfAuthenticated / Authenticate middleware works by default
    Route::get('/login', [AuthController::class, 'showAuthPage'])->name('login');
    Route::get('/register', [AuthController::class, 'showAuthPage'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// ─── Authenticated (Admin + SuperAdmin) ──────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});




Route::prefix('super-admin')->name('super_admin.')->group(function () {
    Route::get('/dashboard',[AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/admins-list',[SuperAdminController::class, 'adminsList'])->name('admins.list');
    Route::post('/admin/change-status/{id}',[SuperAdminController::class, 'changeAdminStatus'])->name('change.status');
    Route::delete('/admin/delete/{id}',[SuperAdminController::class, 'deleteAdmin'])->name('admin.delete');
     // CREATE SUPER ADMIN
    Route::get('/create-super-admin',[SuperAdminController::class, 'createSuperAdmin'])->name('create.super_admin');
    Route::post('/store-super-admin',[SuperAdminController::class, 'storeSuperAdmin'])->name('store.super_admin');
    Route::get('/super-admins-list',[SuperAdminController::class, 'superAdminsList'])->name('list');
    Route::delete('/super-admin/delete/{id}',[SuperAdminController::class, 'deleteSuperAdmin'])->name('delete');
});
