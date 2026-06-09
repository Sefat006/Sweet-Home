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
        // Drop them if they exist to avoid errors during re-migration
        Schema::dropIfExists('flat_tenants');
        Schema::dropIfExists('tenants');

        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->unique()->nullable();         // TNT-000001
            
            // 1. Personal Information
            $table->string('image')->nullable();
            $table->string('name');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->enum('gender', ['male','female','other'])->nullable();
            $table->date('dob')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('religion')->nullable();
            $table->string('nationality')->nullable();

            // Emergency Contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relation')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('emergency_contact_address')->nullable();

            // Marital status & spouse
            $table->enum('marital_status', ['single','married','divorced','widowed'])->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('spouse_contact_number')->nullable();
            $table->string('spouse_father_name')->nullable();
            $table->string('spouse_mother_name')->nullable();
            $table->string('spouse_blood_group')->nullable();
            $table->date('spouse_date_of_birth')->nullable();

            // Children
            $table->integer('no_of_children')->default(0);
            $table->json('children_info')->nullable(); // [{name, gender, dob, birthcertificate}]

            // 2. Occupation
            $table->json('occupation_info')->nullable(); 

            // 3. Education
            $table->json('education_info')->nullable(); 

            // 4. Identity Docs
            $table->string('nid_number')->nullable();
            $table->json('nid_document')->nullable();
            
            $table->string('driving_licence_number')->nullable();
            $table->date('driving_licence_expiry')->nullable();
            $table->json('driving_licence_document')->nullable();
            
            $table->string('passport_number')->nullable();
            $table->date('passport_expiry')->nullable();
            $table->json('passport_document')->nullable();

            // 5. Family / Other Members
            $table->json('members_info')->nullable(); 

            // 6. Domestic Help
            $table->integer('no_of_help')->default(0);
            $table->json('help_info')->nullable(); 

            // 7. Driver
            $table->integer('no_of_driver')->default(0);
            $table->json('driver_info')->nullable(); 

            // 8. Previous Flat Details
            $table->string('prev_owner_name')->nullable();
            $table->string('prev_owner_phone')->nullable();
            $table->text('prev_flat_address')->nullable();
            $table->string('prev_leaving_reason')->nullable();

            // Additional legacy fields just in case it breaks something elsewhere
            $table->text('present_address')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
        
        // Recreate flat_tenants table right here to keep things simple and ensure it is created after tenants
        Schema::create('flat_tenants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flat_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('advance_amount', 12, 2)->default(0);
            $table->json('advance_document')->nullable();            // multiple files
            $table->json('agreement_document')->nullable();          // multiple files
            $table->json('police_form_document')->nullable();        // multiple files
            $table->json('notice_document')->nullable();             // multiple files
            $table->json('house_rent_copy')->nullable();             // multiple files
            $table->enum('status', ['active','inactive'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flat_tenants');
        Schema::dropIfExists('tenants');
    }
};
