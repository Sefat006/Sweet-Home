<?php

use App\Models\User;
use App\Models\Building;
use App\Models\Flat;
use App\Models\Tenant;
use App\Models\FlatTenant;
use App\Models\MonthlyBill;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('rent overview page displays buildings, shows flats, sorts due first, and supports filtering', function () {
    // 1. Create an admin user
    $user = User::factory()->create([
        'role' => 'admin',
        'phone' => '01700000000',
    ]);

    // 2. Create Building A (all paid)
    $buildingA = Building::create([
        'user_id' => $user->id,
        'name' => 'Building A Paid',
        'address' => 'Mirpur, Dhaka',
        'no_of_floor' => 5,
    ]);

    // Create Flat A1 (occupied, bill is paid)
    $flatA1 = Flat::create([
        'building_id' => $buildingA->id,
        'flat_name' => 'A1',
        'floor' => 1,
        'status' => 'occupied',
        'house_rent' => 10000,
        'gas' => 1000,
        'bill_status' => 'active',
    ]);

    // Create Tenant & FlatTenant assignment
    $tenantA = Tenant::create([
        'name' => 'Tenant A',
        'phone' => '01711111111',
        'nid_number' => '1234567890',
    ]);

    $flatTenantA = FlatTenant::create([
        'flat_id' => $flatA1->id,
        'tenant_id' => $tenantA->id,
        'start_date' => '2026-01-01',
        'status' => 'active',
        'advance_amount' => 20000,
    ]);

    // Create paid MonthlyBill for Flat A1
    $billA1 = MonthlyBill::create([
        'flat_id' => $flatA1->id,
        'building_id' => $buildingA->id,
        'tenant_id' => $tenantA->id,
        'flat_tenant_id' => $flatTenantA->id,
        'bill_month' => '2026-06',
        'bill_year' => 2026,
        'bill_month_number' => 6,
        'house_rent' => 10000,
        'gas' => 1000,
        'total_amount' => 11000,
        'paid_amount' => 11000,
        'remaining_amount' => 0,
        'collection_status' => 'paid',
        'generated_by' => $user->id,
    ]);


    // 3. Create Building B (has due rent)
    $buildingB = Building::create([
        'user_id' => $user->id,
        'name' => 'Building B Due',
        'address' => 'Uttara, Dhaka',
        'no_of_floor' => 5,
    ]);

    // Create Flat B1 (occupied, bill is unpaid/due)
    $flatB1 = Flat::create([
        'building_id' => $buildingB->id,
        'flat_name' => 'B1',
        'floor' => 1,
        'status' => 'occupied',
        'house_rent' => 12000,
        'gas' => 1000,
        'bill_status' => 'active',
    ]);

    $tenantB = Tenant::create([
        'name' => 'Tenant B',
        'phone' => '01722222222',
        'nid_number' => '0987654321',
    ]);

    $flatTenantB = FlatTenant::create([
        'flat_id' => $flatB1->id,
        'tenant_id' => $tenantB->id,
        'start_date' => '2026-01-01',
        'status' => 'active',
        'advance_amount' => 24000,
    ]);

    // Create due MonthlyBill for Flat B1
    $billB1 = MonthlyBill::create([
        'flat_id' => $flatB1->id,
        'building_id' => $buildingB->id,
        'tenant_id' => $tenantB->id,
        'flat_tenant_id' => $flatTenantB->id,
        'bill_month' => '2026-06',
        'bill_year' => 2026,
        'bill_month_number' => 6,
        'house_rent' => 12000,
        'gas' => 1000,
        'total_amount' => 13000,
        'paid_amount' => 0,
        'remaining_amount' => 13000,
        'collection_status' => 'due',
        'generated_by' => $user->id,
    ]);


    // 4. Access the Rent Overview page and assert sorting
    // Due building (Building B) must sort first.
    $response = $this->actingAs($user)->get(route('admin.rent.overview'));

    $response->assertStatus(200);

    // Let's assert that Building B appears first in the rows data
    $viewData = $response->original->getData();
    expect($viewData['rows'])->toHaveCount(2);
    expect($viewData['rows'][0]['building']->id)->toBe($buildingB->id);
    expect($viewData['rows'][0]['status'])->toBe('due');
    expect($viewData['rows'][1]['building']->id)->toBe($buildingA->id);
    expect($viewData['rows'][1]['status'])->toBe('paid');

    // Test payment_status filter: paid
    $responsePaidFilter = $this->actingAs($user)->get(route('admin.rent.overview', ['payment_status' => 'paid']));
    $viewDataPaid = $responsePaidFilter->original->getData();
    expect($viewDataPaid['rows'])->toHaveCount(1);
    expect($viewDataPaid['rows'][0]['building']->id)->toBe($buildingA->id);

    // Test payment_status filter: pending
    $responsePendingFilter = $this->actingAs($user)->get(route('admin.rent.overview', ['payment_status' => 'pending']));
    $viewDataPending = $responsePendingFilter->original->getData();
    expect($viewDataPending['rows'])->toHaveCount(1);
    expect($viewDataPending['rows'][0]['building']->id)->toBe($buildingB->id);

    // Test address filter
    $responseAddressFilter = $this->actingAs($user)->get(route('admin.rent.overview', ['address' => 'Mirpur']));
    $viewDataAddress = $responseAddressFilter->original->getData();
    expect($viewDataAddress['rows'])->toHaveCount(1);
    expect($viewDataAddress['rows'][0]['building']->id)->toBe($buildingA->id);

    // Test occupancy filter: vacant (should prune all since all buildings only have occupied flats in this test)
    $responseOccupancyFilter = $this->actingAs($user)->get(route('admin.rent.overview', ['occupancy' => 'vacant']));
    $viewDataOccupancy = $responseOccupancyFilter->original->getData();
    expect($viewDataOccupancy['rows'])->toHaveCount(0);
});

test('rent overview latest bill can be marked as paid', function () {
    $user = User::factory()->create([
        'role' => 'admin',
        'phone' => '01700000000',
    ]);

    $building = Building::create([
        'user_id' => $user->id,
        'name' => 'Building B',
        'address' => 'Uttara, Dhaka',
        'no_of_floor' => 5,
    ]);

    $flat = Flat::create([
        'building_id' => $building->id,
        'flat_name' => 'B1',
        'floor' => 1,
        'status' => 'occupied',
        'house_rent' => 12000,
        'gas' => 1000,
        'bill_status' => 'active',
    ]);

    $tenant = Tenant::create([
        'name' => 'Tenant B',
        'phone' => '01722222222',
        'nid_number' => '0987654321',
    ]);

    $flatTenant = FlatTenant::create([
        'flat_id' => $flat->id,
        'tenant_id' => $tenant->id,
        'start_date' => '2026-01-01',
        'status' => 'active',
        'advance_amount' => 24000,
    ]);

    $bill = MonthlyBill::create([
        'flat_id' => $flat->id,
        'building_id' => $building->id,
        'tenant_id' => $tenant->id,
        'flat_tenant_id' => $flatTenant->id,
        'bill_month' => '2026-06',
        'bill_year' => 2026,
        'bill_month_number' => 6,
        'house_rent' => 12000,
        'gas' => 1000,
        'total_amount' => 13000,
        'paid_amount' => 0,
        'remaining_amount' => 13000,
        'collection_status' => 'due',
        'generated_by' => $user->id,
    ]);

    // Call POST route to toggle/mark as paid
    $response = $this->actingAs($user)
        ->post(route('admin.rent.overview.toggle', $bill->id));

    $response->assertRedirect(route('admin.rent.overview'));
    $response->assertSessionHas('success');

    $bill->refresh();
    expect($bill->collection_status)->toBe('paid');
    expect((float) $bill->remaining_amount)->toBe(0.0);
    expect((float) $bill->paid_amount)->toBe(13000.0);
});
