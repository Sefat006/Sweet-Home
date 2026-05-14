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
        Schema::create('building_expenses', function (Blueprint $table) {
                        $table->id();
            $table->foreignId('building_id')->constrained()->cascadeOnDelete();
 
            $table->string('expense_month');            // YYYY-MM
            $table->unsignedSmallInteger('expense_year');
            $table->unsignedTinyInteger('expense_month_number');
 
            // Expense breakdown
            $table->decimal('security_bill',      12, 2)->default(0);
            $table->decimal('cleaning_bill',      12, 2)->default(0);
            $table->decimal('cleaning_material',  12, 2)->default(0);
            $table->decimal('maintenance',        12, 2)->default(0);
            $table->decimal('eid_bonus',          12, 2)->default(0);
            $table->decimal('material_replacement',12,2)->default(0);
            $table->decimal('flat_cleaning',      12, 2)->default(0);
            $table->decimal('society_cost',       12, 2)->default(0);
            $table->decimal('driver_cost',        12, 2)->default(0);
            $table->decimal('other',              12, 2)->default(0);
            $table->decimal('total_expense',      12, 2)->default(0);
 
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
 
            $table->timestamps();
            $table->softDeletes();
 
            $table->unique(['building_id', 'expense_month']); // one record per building per month
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('building_expenses');
    }
};
