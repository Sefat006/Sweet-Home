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
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->tinyInteger('no_of_floor')->unsigned()->default(1);
            $table->text('address');
            $table->string('holding_tax_number')->nullable();
            $table->date('holding_tax_clearance_up_to')->nullable();
            $table->string('holding_tax_document')->nullable();
            $table->string('dolil_document')->nullable();
            $table->string('noksha_document')->nullable();
            $table->string('mutation_document')->nullable();
            $table->string('khajna_document')->nullable();
            $table->date('khajna_clearance_up_to')->nullable();
            $table->text('alert_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
