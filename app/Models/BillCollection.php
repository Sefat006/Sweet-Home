<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillCollection extends Model
{
   use HasFactory;
 
    protected $fillable = [
        'monthly_bill_id', 'flat_id', 'tenant_id',
        'amount', 'collection_date', 'payment_method',
        'transaction_reference', 'notes', 'collected_by',
    ];
 
    protected $casts = [
        'amount'          => 'decimal:2',
        'collection_date' => 'date',
    ];
 
    public function monthlyBill()  { return $this->belongsTo(MonthlyBill::class); }
    public function flat()         { return $this->belongsTo(Flat::class); }
    public function tenant()       { return $this->belongsTo(Tenant::class); }
    public function collectedBy()  { return $this->belongsTo(User::class, 'collected_by'); }
 
    // After save, recalculate parent bill
    protected static function booted(): void
    {
        static::saved(function (BillCollection $collection) {
            $collection->monthlyBill->recalculate();
        });
        static::deleted(function (BillCollection $collection) {
            $collection->monthlyBill->recalculate();
        });
    }
}
