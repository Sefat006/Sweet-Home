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
        Schema::create('flats', function (Blueprint $table) {
                        $table->id();
            $table->foreignId('building_id')->constrained('buildings')->cascadeOnDelete();
            $table->string('flat_name');
            $table->string('intercom_number', 20)->nullable();
            $table->tinyInteger('floor')->unsigned()->nullable();
            $table->enum('status', ['vacant', 'occupied'])->default('vacant');
            $table->enum('available_for', ['rent', 'sale', 'lease'])->nullable();
            $table->string('flat_size')->nullable();
            $table->text('flat_details')->nullable();
            $table->string('image')->nullable();
            $table->decimal('house_rent', 10, 2)->default(0);
            $table->decimal('wasa', 10, 2)->default(0);
            $table->decimal('common_electricity', 10, 2)->default(0);
            $table->decimal('gas', 10, 2)->default(0);
            $table->decimal('utility', 10, 2)->default(0);
            $table->decimal('parking', 10, 2)->default(0);
            $table->decimal('society_bill', 10, 2)->default(0);
            $table->decimal('security', 10, 2)->default(0);
            $table->decimal('other', 10, 2)->default(0);
            $table->enum('bill_status', ['active', 'inactive'])->default('inactive');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flats');
    }
};
