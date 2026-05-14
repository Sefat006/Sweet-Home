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
        Schema::create('flat_tenants', function (Blueprint $table) {
           $table->id();
            $table->foreignId('flat_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('advance_amount', 12, 2)->default(0);
            $table->string('advance_document')->nullable();            // advance copy
            $table->string('agreement_document')->nullable();          // agreement copy
            $table->string('police_form_document')->nullable();        // police form
            $table->string('notice_document')->nullable();             // notice copy
            $table->string('house_rent_copy')->nullable();             // house rent copy
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
    }
};
