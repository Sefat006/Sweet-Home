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
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('religion')->nullable()->after('blood_group');
            $table->string('nationality')->nullable()->after('religion');
            
            $table->string('spouse_name')->nullable()->after('marital_status');
            $table->string('spouse_contact_number')->nullable()->after('spouse_name');
            $table->string('spouse_father_name')->nullable()->after('spouse_contact_number');
            $table->string('spouse_mother_name')->nullable()->after('spouse_father_name');
            $table->string('spouse_blood_group')->nullable()->after('spouse_mother_name');
            $table->date('spouse_date_of_birth')->nullable()->after('spouse_blood_group');

            $table->string('passport_number')->nullable()->after('nid_document');
            $table->date('passport_expiry')->nullable()->after('passport_number');
            $table->string('passport_document')->nullable()->after('passport_expiry');

            $table->string('driving_licence_number')->nullable()->after('passport_document');
            $table->date('driving_licence_expiry')->nullable()->after('driving_licence_number');
            $table->string('driving_licence_document')->nullable()->after('driving_licence_expiry');

            $table->string('occupation_company')->nullable()->after('occupation');
            $table->text('occupation_address')->nullable()->after('occupation_company');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'religion',
                'nationality',
                'spouse_name',
                'spouse_contact_number',
                'spouse_father_name',
                'spouse_mother_name',
                'spouse_blood_group',
                'spouse_date_of_birth',
                'passport_number',
                'passport_expiry',
                'passport_document',
                'driving_licence_number',
                'driving_licence_expiry',
                'driving_licence_document',
                'occupation_company',
                'occupation_address'
            ]);
        });
    }
};
