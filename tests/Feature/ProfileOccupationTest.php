<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin profile edit page loads successfully', function () {
    $user = User::factory()->create([
        'role' => 'admin',
        'phone' => '01700000000',
    ]);

    $response = $this->actingAs($user)->get(route('admin.profile.edit'));

    $response->assertStatus(200);
});

test('admin profile can be updated with multiple occupations and files', function () {
    $user = User::factory()->create([
        'role' => 'admin',
        'phone' => '01700000000',
    ]);

    // Mock UploadedFiles
    $jobDoc = UploadedFile::fake()->create('job_verification.pdf', 100);
    $tradeDoc = UploadedFile::fake()->create('trade_license.jpg', 200);
    $eduDoc1 = UploadedFile::fake()->create('bsc_transcript.pdf', 150);
    $eduDoc2 = UploadedFile::fake()->create('mba_certificate.pdf', 250);

    $payload = [
        'name' => 'John Owner',
        'email' => 'owner@example.com',
        'phone' => '0123456789',
        
        // Occupation fields
        'occupation_type' => [
            0 => 'job',
            1 => 'business'
        ],
        'occupation_company' => [
            0 => 'Google'
        ],
        'occupation_address' => [
            0 => 'Mountain View'
        ],
        'occupation_document' => [
            0 => [$jobDoc]
        ],
        'business_name' => [
            1 => 'Tech Cafe'
        ],
        'business_address' => [
            1 => 'Silicon Valley'
        ],
        'trade_license_document' => [
            1 => [$tradeDoc]
        ],

        // Education fields
        'edu_exam' => [
            0 => 'B.Sc in CSE',
            1 => 'MBA'
        ],
        'edu_institution' => [
            0 => 'Dhaka University',
            1 => 'IBA'
        ],
        'edu_year' => [
            0 => 2018,
            1 => 2021
        ],
        'edu_document' => [
            0 => [$eduDoc1],
            1 => [$eduDoc2]
        ]
    ];

    $response = $this->actingAs($user)
        ->from(route('admin.profile.edit'))
        ->put(route('admin.profile.update'), $payload);

    $response->assertRedirect(route('admin.profile.edit'));
    $response->assertSessionHasNoErrors();

    $user->refresh();

    // Verify occupation_info JSON column was correctly updated
    expect($user->occupation_info)->toBeArray();
    expect($user->occupation_info)->toHaveCount(2);

    // Assert Job entry details
    expect($user->occupation_info[0]['type'])->toBe('job');
    expect($user->occupation_info[0]['company'])->toBe('Google');
    expect($user->occupation_info[0]['address'])->toBe('Mountain View');
    expect($user->occupation_info[0]['documents'])->toBeArray();
    expect($user->occupation_info[0]['documents'])->toHaveCount(1);
    
    // Assert Business entry details
    expect($user->occupation_info[1]['type'])->toBe('business');
    expect($user->occupation_info[1]['business_name'])->toBe('Tech Cafe');
    expect($user->occupation_info[1]['business_address'])->toBe('Silicon Valley');
    expect($user->occupation_info[1]['trade_docs'])->toBeArray();
    expect($user->occupation_info[1]['trade_docs'])->toHaveCount(1);

    // Verify education JSON column was correctly updated
    expect($user->education)->toBeArray();
    expect($user->education)->toHaveCount(2);

    expect($user->education[0]['exam'])->toBe('B.Sc in CSE');
    expect($user->education[0]['institute'])->toBe('Dhaka University');
    expect($user->education[0]['year'])->toBe(2018);
    expect($user->education[0]['documents'])->toBeArray();
    expect($user->education[0]['documents'])->toHaveCount(1);

    expect($user->education[1]['exam'])->toBe('MBA');
    expect($user->education[1]['institute'])->toBe('IBA');
    expect($user->education[1]['year'])->toBe(2021);
    expect($user->education[1]['documents'])->toBeArray();
    expect($user->education[1]['documents'])->toHaveCount(1);

    // Clean up uploaded files in public path
    foreach ($user->occupation_info[0]['documents'] as $doc) {
        if (file_exists(public_path($doc))) {
            unlink(public_path($doc));
        }
    }
    foreach ($user->occupation_info[1]['trade_docs'] as $doc) {
        if (file_exists(public_path($doc))) {
            unlink(public_path($doc));
        }
    }
    foreach ($user->education[0]['documents'] as $doc) {
        if (file_exists(public_path($doc))) {
            unlink(public_path($doc));
        }
    }
    foreach ($user->education[1]['documents'] as $doc) {
        if (file_exists(public_path($doc))) {
            unlink(public_path($doc));
        }
    }
});
