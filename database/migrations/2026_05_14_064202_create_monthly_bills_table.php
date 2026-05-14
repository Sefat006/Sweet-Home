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
        Schema::create('monthly_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flat_id')->constrained()->cascadeOnDelete();
            $table->foreignId('building_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('flat_tenant_id')->nullable()->constrained()->nullOnDelete(); // which assignment period
 
            $table->string('bill_month');           // format: 2026-05 (YYYY-MM)
            $table->unsignedSmallInteger('bill_year');
            $table->unsignedTinyInteger('bill_month_number'); // 1-12
 
            // Rent breakdown (copied from flat at time of generation)
            $table->decimal('house_rent',         12, 2)->default(0);
            $table->decimal('wasa',               12, 2)->default(0);
            $table->decimal('common_electricity', 12, 2)->default(0);
            $table->decimal('gas',                12, 2)->default(0);
            $table->decimal('utility',            12, 2)->default(0);
            $table->decimal('parking',            12, 2)->default(0);
            $table->decimal('society_bill',       12, 2)->default(0);
            $table->decimal('security',           12, 2)->default(0);
            $table->decimal('other',              12, 2)->default(0);
 
            $table->decimal('total_amount',     12, 2)->default(0);
            $table->decimal('paid_amount',      12, 2)->default(0);
            $table->decimal('remaining_amount', 12, 2)->default(0);
 
            $table->decimal('previous_due', 12, 2)->default(0); // carried forward from last month
 
            $table->enum('collection_status', ['due', 'partial', 'paid'])->default('due');
 
            $table->date('payment_date')->nullable();
            $table->text('notes')->nullable();
 
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('collected_by')->nullable()->constrained('users')->nullOnDelete();
 
            $table->timestamps();
            $table->softDeletes();
 
            // One bill per flat per month
            $table->unique(['flat_id', 'bill_month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_bills');
    }
};
