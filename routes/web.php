<?php

use App\Http\Controllers\Back\Admin\BuildingController;
use App\Http\Controllers\Back\Admin\BuildingExpenseController;
use App\Http\Controllers\Back\Admin\FlatController;
use App\Http\Controllers\Back\Admin\MonthlyBillController;
use App\Http\Controllers\Back\Admin\RentOverviewController;
use App\Http\Controllers\Back\Admin\UtilityBillController;
use App\Http\Controllers\Back\Admin\ProfileController;
use App\Http\Controllers\Back\Admin\TenantController;
use App\Http\Controllers\Front\WelcomeController;
use App\Http\Controllers\Back\Auth\AuthController;
use App\Http\Controllers\Back\SuperAdmin\SuperAdminController;
use App\Http\Controllers\Back\SuperAdmin\UserManagementController;
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




Route::middleware(['auth'])->prefix('super-admin')->name('super_admin.')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/admins-list', [SuperAdminController::class, 'adminsList'])->name('admins.list');
    Route::post('/admin/change-status/{id}', [SuperAdminController::class, 'changeAdminStatus'])->name('change.status');
    Route::delete('/admin/delete/{id}', [SuperAdminController::class, 'deleteAdmin'])->name('admin.delete');
    // CREATE SUPER ADMIN
    Route::get('/create-super-admin', [SuperAdminController::class, 'createSuperAdmin'])->name('create.super_admin');
    Route::post('/store-super-admin', [SuperAdminController::class, 'storeSuperAdmin'])->name('store.super_admin');
    Route::get('/super-admins-list', [SuperAdminController::class, 'superAdminsList'])->name('list');
    Route::delete('/super-admin/delete/{id}', [SuperAdminController::class, 'deleteSuperAdmin'])->name('delete');


    // Admin profile management
    Route::get('/admin/{id}',                        [UserManagementController::class, 'show'])->name('admin.show');
    Route::get('/admin/{id}/document/{field}/download', [UserManagementController::class, 'downloadDocument'])->name('admin.document.download');
    Route::post('/admin/{id}/verify-document',       [UserManagementController::class, 'verifyDocument'])->name('admin.document.verify');
});




// ─── Admin ────────────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Profile — view (read-only)
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

    // Profile — edit form (GET) + update (PUT)
    Route::get('/profile/edit',   [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');


    // Building Routes
    Route::get('/buildings', [BuildingController::class, 'index'])->name('building.index');
    Route::get('/building/create', [BuildingController::class, 'create'])->name('building.create');
    Route::post('/building/store', [BuildingController::class, 'store'])->name('building.store');
    Route::get('/building/{id}/show', [BuildingController::class, 'show'])->name('building.show');
    Route::get('/building/{id}/edit', [BuildingController::class, 'edit'])->name('building.edit');
    Route::put('/building/{id}/update', [BuildingController::class, 'update'])->name('building.update');
    Route::delete('/building/{id}/delete', [BuildingController::class, 'destroy'])->name('building.destroy');


    // Flat Routes
    Route::get('/building/{buildingId}/flats', [FlatController::class, 'index'])->name('flats.index');
    Route::get('/building/{buildingId}/flat/create', [FlatController::class, 'create'])->name('flats.create');
    Route::post('/building/{buildingId}/flat/store', [FlatController::class, 'store'])->name('flats.store');
    Route::get('/building/{buildingId}/flat/{flatId}/show', [FlatController::class, 'show'])->name('flats.show');
    Route::get('/building/{buildingId}/flat/{flatId}/edit', [FlatController::class, 'edit'])->name('flats.edit');
    Route::put('/building/{buildingId}/flat/{flatId}/update', [FlatController::class, 'update'])->name('flats.update');
    Route::delete('/building/{buildingId}/flat/{flatId}/delete', [FlatController::class, 'destroy'])->name('flats.destroy');



    // Tenant Routes (nested under building > flat)
    Route::get('/building/{buildingId}/flat/{flatId}/tenants',              [TenantController::class, 'index'])->name('tenants.index');
    Route::get('/building/{buildingId}/flat/{flatId}/tenant/enroll',        [TenantController::class, 'enroll'])->name('tenants.enroll');
    Route::get('/building/{buildingId}/flat/{flatId}/tenant/search',        [TenantController::class, 'search'])->name('tenants.search');
    Route::get('/building/{buildingId}/flat/{flatId}/tenant/create',        [TenantController::class, 'create'])->name('tenants.create');
    Route::post('/building/{buildingId}/flat/{flatId}/tenant/store',        [TenantController::class, 'store'])->name('tenants.store');
    Route::post('/building/{buildingId}/flat/{flatId}/tenant/assign',       [TenantController::class, 'assign'])->name('tenants.assign');
    Route::get('/building/{buildingId}/flat/{flatId}/tenant/{tenantId}',    [TenantController::class, 'show'])->name('tenants.show');
    Route::get('/building/{buildingId}/flat/{flatId}/tenant/{tenantId}/download', [TenantController::class, 'download'])->name('tenants.download');
    Route::get('/building/{buildingId}/flat/{flatId}/tenant/{tenantId}/edit',    [TenantController::class, 'edit'])->name('tenants.edit');
    Route::put('/building/{buildingId}/flat/{flatId}/tenant/{tenantId}/update',  [TenantController::class, 'update'])->name('tenants.update');
    Route::post('/building/{buildingId}/flat/{flatId}/tenant/{tenantId}/vacate', [TenantController::class, 'vacate'])->name('tenants.vacate');


    // Monthly Bill Routes (nested: building > flat > bills)
    Route::get('/building/{buildingId}/flat/{flatId}/bills',                             [MonthlyBillController::class, 'index'])->name('bills.index');
    Route::get('/building/{buildingId}/flat/{flatId}/bill/create',                       [MonthlyBillController::class, 'create'])->name('bills.create');
    Route::post('/building/{buildingId}/flat/{flatId}/bill/store',                       [MonthlyBillController::class, 'store'])->name('bills.store');
    Route::get('/building/{buildingId}/flat/{flatId}/bill/{billId}',                     [MonthlyBillController::class, 'show'])->name('bills.show');
    Route::delete('/building/{buildingId}/flat/{flatId}/bill/{billId}/delete',           [MonthlyBillController::class, 'destroy'])->name('bills.destroy');
    Route::get('/building/{buildingId}/flat/{flatId}/bill/{billId}/collect',             [MonthlyBillController::class, 'collectForm'])->name('bills.collect.form');
    Route::post('/building/{buildingId}/flat/{flatId}/bill/{billId}/collect',            [MonthlyBillController::class, 'collectStore'])->name('bills.collect.store');
    Route::delete('/building/{buildingId}/flat/{flatId}/bill/{billId}/collection/{collectionId}/delete', [MonthlyBillController::class, 'deleteCollection'])->name('bills.collection.delete');
    Route::get('/building/{buildingId}/flat/{flatId}/bill/{billId}/export-pdf',                          [MonthlyBillController::class, 'exportPdf'])->name('bills.export.pdf');

    // Utility Bill Routes (nested under building)
    Route::get('/building/{buildingId}/utility-bills',                    [UtilityBillController::class, 'index'])->name('utility.index');
    Route::get('/building/{buildingId}/utility-bill/create',              [UtilityBillController::class, 'create'])->name('utility.create');
    Route::post('/building/{buildingId}/utility-bill/store',              [UtilityBillController::class, 'store'])->name('utility.store');
    Route::get('/building/{buildingId}/utility-bill/{billId}/edit',       [UtilityBillController::class, 'edit'])->name('utility.edit');
    Route::put('/building/{buildingId}/utility-bill/{billId}/update',     [UtilityBillController::class, 'update'])->name('utility.update');
    Route::post('/building/{buildingId}/utility-bill/{billId}/mark-paid', [UtilityBillController::class, 'markPaid'])->name('utility.mark-paid');
    Route::delete('/building/{buildingId}/utility-bill/{billId}/delete',  [UtilityBillController::class, 'destroy'])->name('utility.destroy');

    // Building Expenses Routes
    Route::get('/building/{buildingId}/expenses',                   [BuildingExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/building/{buildingId}/expense/create',             [BuildingExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/building/{buildingId}/expense/store',             [BuildingExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/building/{buildingId}/expense/{expenseId}/edit',   [BuildingExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('/building/{buildingId}/expense/{expenseId}/update', [BuildingExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/building/{buildingId}/expense/{expenseId}/delete', [BuildingExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Rent Overview Routes
    Route::get('/rent-overview',                          [RentOverviewController::class, 'index'])->name('rent.overview');
    Route::post('/rent-overview/toggle-paid/{billId}',    [RentOverviewController::class, 'togglePaid'])->name('rent.overview.toggle');
});

// ─── Utility Deployment Routes (Shared Hosting) ──────────────────────────────
Route::prefix('deploy-setup')->group(function () {
    // Run migrations
    Route::get('/migrate', function () {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            return response("Migrations run successfully:\n" . \Illuminate\Support\Facades\Artisan::output(), 200)
                ->header('Content-Type', 'text/plain');
        } catch (\Exception $e) {
            return response("Migration Error: " . $e->getMessage(), 500)
                ->header('Content-Type', 'text/plain');
        }
    });

    // Create storage link symlink
    Route::get('/storage-link', function () {
        try {
            \Illuminate\Support\Facades\Artisan::call('storage:link');
            return response("Storage symlink created successfully:\n" . \Illuminate\Support\Facades\Artisan::output(), 200)
                ->header('Content-Type', 'text/plain');
        } catch (\Exception $e) {
            return response("Storage Link Error: " . $e->getMessage(), 500)
                ->header('Content-Type', 'text/plain');
        }
    });

    // Clear Laravel caches
    Route::get('/clear-cache', function () {
        try {
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            return response("All Laravel caches cleared successfully!\n", 200)
                ->header('Content-Type', 'text/plain');
        } catch (\Exception $e) {
            return response("Cache Clear Error: " . $e->getMessage(), 500)
                ->header('Content-Type', 'text/plain');
        }
    });
});
