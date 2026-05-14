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
        Schema::create('utility_bills', function (Blueprint $table) {
           $table->id();
            $table->foreignId('building_id')->constrained()->cascadeOnDelete();
 
            $table->enum('bill_type', [
                'wasa',         // Water/sewerage
                'titas_gas',    // TITAS gas
                'holding_tax',  // Holding tax
                'electricity',  // Common area electricity
                'other',
            ]);
 
            $table->string('billing_name')->nullable();          // e.g. "WASA Bill - Block A"
            $table->string('bill_month')->nullable();            // YYYY-MM (for monthly bills)
            $table->string('bill_year')->nullable();             // for yearly bills like holding tax
            $table->string('invoice_number')->nullable();
            $table->date('due_date')->nullable();
 
            $table->decimal('total_amount',     12, 2)->default(0);
            $table->decimal('paid_amount',      12, 2)->default(0);
            $table->decimal('remaining_amount', 12, 2)->default(0);
 
            $table->enum('payment_status', ['due', 'partial', 'paid'])->default('due');
            $table->date('payment_date')->nullable();
            $table->enum('payment_method', ['cash','bank','bkash','nagad','rocket','other'])->nullable();
            $table->string('transaction_reference')->nullable();
 
            $table->string('document')->nullable();              // bill scan/photo
            $table->text('notes')->nullable();
 
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utility_bills');
    }
};
