<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
           $table->id();
 
            // Role: 'super_admin' | 'admin'
            $table->enum('role', ['super_admin', 'admin'])->default('admin');
 
            // Admin unique ID (e.g., ADM-000001) — null for super_admin
            $table->string('admin_id', 20)->unique()->nullable();
 
            // --- Registration step (minimum info) ---
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('password');
 
            // --- Super Admin approval ---
            // pending | approved | rejected
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
 
            // --- Profile completion flag ---
            $table->boolean('profile_completed')->default(false);
 
            // --- Owner profile info (filled after approval) ---
            $table->string('image')->nullable();           // profile photo path
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->string('nid_number')->nullable();
            $table->string('nid_document')->nullable();    // file path
            $table->string('passport_number')->nullable();
            $table->date('passport_expiry')->nullable();
            $table->string('passport_document')->nullable();
            $table->string('tin_number')->nullable();
            $table->string('tin_document')->nullable();
            $table->string('driving_licence_number')->nullable();
            $table->date('driving_licence_expiry')->nullable();
            $table->string('driving_licence_document')->nullable();
            $table->text('emergency_contact')->nullable(); // JSON: name, phone, relation
 
            // --- Occupation ---
            $table->string('occupation_position')->nullable();
            $table->string('occupation_company')->nullable();
            $table->text('occupation_address')->nullable();
            $table->string('occupation_document')->nullable();
 
            // Standard Laravel fields
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // safe delete — super_admin can "delete" admin
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
