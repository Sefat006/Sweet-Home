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
        Schema::create('bill_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monthly_bill_id')->constrained()->cascadeOnDelete();
            $table->foreignId('flat_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
 
            $table->decimal('amount', 12, 2);          // amount collected this time
            $table->date('collection_date');
            $table->enum('payment_method', ['cash', 'bank', 'bkash', 'nagad', 'rocket', 'other'])->default('cash');
            $table->string('transaction_reference')->nullable();
            $table->text('notes')->nullable();
 
            $table->foreignId('collected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_collections');
    }
};
