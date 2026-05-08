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
        Schema::create('managers', function (Blueprint $table) {
             $table->id();
 
            // যে Admin এই Manager তৈরি করেছে
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade'); // Admin delete হলে তার সব Manager ও delete
 
            // Manager unique ID (e.g., MGR-000001)
            $table->string('manager_id', 20)->unique();
 
            // --- Login credentials ---
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('password');
 
            // active | inactive (Admin চাইলে deactivate করতে পারবে)
            $table->enum('status', ['active', 'inactive'])->default('active');
 
            // --- Profile info (Admin বা Manager নিজে fill করবে) ---
            $table->boolean('profile_completed')->default(false);
            $table->string('image')->nullable();
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->string('nid_number')->nullable();
            $table->string('nid_document')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
 
            // --- Occupation ---
            $table->string('occupation_position')->nullable();
            $table->string('occupation_company')->nullable();
            $table->text('occupation_address')->nullable();
 
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // safe delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('managers');
    }
};
