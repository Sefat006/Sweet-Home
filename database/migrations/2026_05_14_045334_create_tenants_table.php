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
        Schema::create('tenants', function (Blueprint $table) {
           $table->id();
            $table->string('tenant_id')->unique()->nullable();         // TNT-000001
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('nid_number')->nullable();
            $table->string('nid_document')->nullable();
            $table->string('birth_cert_number')->nullable();
            $table->string('birth_cert_document')->nullable();
            $table->date('dob')->nullable();
            $table->string('blood_group')->nullable();
            $table->enum('gender', ['male','female','other'])->nullable();
            $table->enum('marital_status', ['single','married','divorced','widowed'])->nullable();
            $table->string('occupation')->nullable();
            $table->string('occupation_document')->nullable();         // job card / trade licence
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relation')->nullable();
            $table->text('previous_rent_info')->nullable();            // previous address / landlord
            $table->text('reason_to_change')->nullable();
            $table->unsignedTinyInteger('family_members_count')->default(1);
            $table->json('family_members')->nullable();                // [{name, relation, nid}]
            $table->string('vehicle_info')->nullable();
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
        Schema::dropIfExists('tenants');
    }
};
