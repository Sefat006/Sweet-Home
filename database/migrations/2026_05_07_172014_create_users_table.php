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

            // --- Education ---
            $table->json('education')->nullable();

            // --- Father Info ---
            $table->string('father_name')->nullable();
            $table->date('father_dob')->nullable();
            $table->string('father_contact')->nullable();
            $table->string('father_email')->nullable();
            $table->text('father_present_address')->nullable();
            $table->text('father_permanent_address')->nullable();
            $table->string('father_status')->nullable();
            $table->string('father_blood_group')->nullable();
            $table->string('father_birth_certificate')->nullable();
            $table->string('father_nid_number')->nullable();
            $table->json('father_education')->nullable();
            $table->boolean('father_reminder')->default(false);
            $table->string('father_occupation_position')->nullable();
            $table->string('father_occupation_company')->nullable();
            $table->text('father_occupation_address')->nullable();

            // --- Mother Info ---
            $table->string('mother_name')->nullable();
            $table->date('mother_dob')->nullable();
            $table->string('mother_contact')->nullable();
            $table->string('mother_email')->nullable();
            $table->text('mother_present_address')->nullable();
            $table->text('mother_permanent_address')->nullable();
            $table->string('mother_status')->nullable();
            $table->date('mother_expired_date')->nullable();
            $table->string('mother_blood_group')->nullable();
            $table->string('mother_birth_certificate')->nullable();
            $table->string('mother_nid_number')->nullable();
            $table->json('mother_education')->nullable();
            $table->boolean('mother_reminder')->default(false);
            $table->string('mother_occupation_position')->nullable();
            $table->string('mother_occupation_company')->nullable();
            $table->text('mother_occupation_address')->nullable();

            // --- Spouse & Children ---
            $table->integer('no_of_spouse')->default(0);
            $table->json('spouse_info')->nullable();
            $table->integer('no_of_children')->default(0);
            $table->json('children_info')->nullable();

            // --- Vehicle Info ---
            $table->integer('no_of_cars')->default(0);
            $table->text('car_details')->nullable();
            $table->string('car_details_document')->nullable();
            $table->text('driver_details')->nullable();

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
