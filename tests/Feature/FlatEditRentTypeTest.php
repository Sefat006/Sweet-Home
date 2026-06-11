<?php

use App\Models\User;
use App\Models\Building;
use App\Models\Flat;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('flat edit view sets correct default selected option based on database values', function () {
    $user = User::factory()->create([
        'role' => 'admin',
        'phone' => '01700000000',
    ]);

    $building = Building::create([
        'user_id' => $user->id,
        'name' => 'Test Building',
        'address' => 'Dhaka',
        'no_of_floor' => 3,
    ]);

    // Case 1: Flat with all zero values should default to "Select" (empty string)
    $flatZero = Flat::create([
        'building_id' => $building->id,
        'flat_name' => '101',
        'house_rent' => 0,
        'gas' => 0,
        'bill_status' => 'inactive',
    ]);

    $response = $this->actingAs($user)->get(route('admin.flats.edit', [$building->id, $flatZero->id]));
    $response->assertStatus(200);
    $response->assertSee('<option value="" selected>Select</option>', false);

    // Case 2: Flat with only house rent should default to "house_only"
    $flatHouseOnly = Flat::create([
        'building_id' => $building->id,
        'flat_name' => '102',
        'house_rent' => 15000,
        'gas' => 0,
        'bill_status' => 'inactive',
    ]);

    $response = $this->actingAs($user)->get(route('admin.flats.edit', [$building->id, $flatHouseOnly->id]));
    $response->assertStatus(200);
    $response->assertSee('<option value="house_only" selected>', false);

    // Case 3: Flat with other fields should default to "full_breakdown"
    $flatFull = Flat::create([
        'building_id' => $building->id,
        'flat_name' => '103',
        'house_rent' => 15000,
        'gas' => 1000,
        'bill_status' => 'inactive',
    ]);

    $response = $this->actingAs($user)->get(route('admin.flats.edit', [$building->id, $flatFull->id]));
    $response->assertStatus(200);
    $response->assertSee('<option value="full_breakdown" selected>', false);
});

test('saving behavior remains unchanged and saves correctly', function () {
    $user = User::factory()->create([
        'role' => 'admin',
        'phone' => '01700000000',
    ]);

    $building = Building::create([
        'user_id' => $user->id,
        'name' => 'Test Building',
        'address' => 'Dhaka',
        'no_of_floor' => 3,
    ]);

    $flat = Flat::create([
        'building_id' => $building->id,
        'flat_name' => '201',
        'house_rent' => 12000,
        'gas' => 800,
        'wasa' => 200,
        'bill_status' => 'inactive',
    ]);

    // Update to House Rent Only (UI submits house_rent value and resets others to 0)
    $response = $this->actingAs($user)->put(route('admin.flats.update', [$building->id, $flat->id]), [
        'flat_name'          => '201-Updated',
        'status'             => 'vacant',
        'bill_status'        => 'active',
        'house_rent'         => 18000,
        'wasa'               => 0,
        'common_electricity' => 0,
        'gas'                => 0,
        'utility'            => 0,
        'parking'            => 0,
        'society_bill'       => 0,
        'security'           => 0,
        'other'              => 0,
    ]);

    $response->assertRedirect(route('admin.flats.index', $building->id));
    $flat->refresh();

    expect($flat->flat_name)->toBe('201-Updated');
    expect((float) $flat->house_rent)->toBe(18000.0);
    expect((float) $flat->wasa)->toBe(0.0);
    expect((float) $flat->gas)->toBe(0.0);

    // Update to Full Breakdown (UI submits all breakdown values)
    $response = $this->actingAs($user)->put(route('admin.flats.update', [$building->id, $flat->id]), [
        'flat_name'          => '201-Updated',
        'status'             => 'vacant',
        'bill_status'        => 'active',
        'house_rent'         => 18000,
        'wasa'               => 300,
        'common_electricity' => 250,
        'gas'                => 1000,
        'utility'            => 150,
        'parking'            => 500,
        'society_bill'       => 200,
        'security'           => 100,
        'other'              => 50,
    ]);

    $response->assertRedirect(route('admin.flats.index', $building->id));
    $flat->refresh();

    expect((float) $flat->house_rent)->toBe(18000.0);
    expect((float) $flat->wasa)->toBe(300.0);
    expect((float) $flat->common_electricity)->toBe(250.0);
    expect((float) $flat->gas)->toBe(1000.0);
    expect((float) $flat->utility)->toBe(150.0);
    expect((float) $flat->parking)->toBe(500.0);
    expect((float) $flat->society_bill)->toBe(200.0);
    expect((float) $flat->security)->toBe(100.0);
    expect((float) $flat->other)->toBe(50.0);
});
