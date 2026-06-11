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
        Schema::table('buildings', function (Blueprint $table) {
            // For bill slip PDF: bank transfer info (multiline text)
            $table->text('bank_info')->nullable()->after('alert_notes');
            // For bill slip PDF: additional contact note line
            $table->string('contact_note', 300)->nullable()->after('bank_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn(['bank_info', 'contact_note']);
        });
    }
};
