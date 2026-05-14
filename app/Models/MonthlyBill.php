<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonthlyBill extends Model
{
     use HasFactory, SoftDeletes;
 
    protected $fillable = [
        'flat_id', 'building_id', 'tenant_id', 'flat_tenant_id',
        'bill_month', 'bill_year', 'bill_month_number',
        'house_rent', 'wasa', 'common_electricity', 'gas', 'utility',
        'parking', 'society_bill', 'security', 'other',
        'total_amount', 'paid_amount', 'remaining_amount', 'previous_due',
        'collection_status', 'payment_date', 'notes',
        'generated_by', 'collected_by',
    ];
 
    protected $casts = [
        'house_rent'         => 'decimal:2',
        'wasa'               => 'decimal:2',
        'common_electricity' => 'decimal:2',
        'gas'                => 'decimal:2',
        'utility'            => 'decimal:2',
        'parking'            => 'decimal:2',
        'society_bill'       => 'decimal:2',
        'security'           => 'decimal:2',
        'other'              => 'decimal:2',
        'total_amount'       => 'decimal:2',
        'paid_amount'        => 'decimal:2',
        'remaining_amount'   => 'decimal:2',
        'previous_due'       => 'decimal:2',
        'payment_date'       => 'date',
    ];
 
    public function flat()        { return $this->belongsTo(Flat::class); }
    public function building()    { return $this->belongsTo(Building::class); }
    public function tenant()      { return $this->belongsTo(Tenant::class); }
    public function flatTenant()  { return $this->belongsTo(FlatTenant::class); }
    public function generatedBy() { return $this->belongsTo(User::class, 'generated_by'); }
    public function collectedBy() { return $this->belongsTo(User::class, 'collected_by'); }
    public function collections() { return $this->hasMany(BillCollection::class); }
 
    // Auto-recalculate remaining & status after each collection
    public function recalculate(): void
    {
        $paid = (float) $this->collections()->sum('amount');
        $total = (float) $this->total_amount + (float) $this->previous_due;
        $this->paid_amount      = $paid;
        $this->remaining_amount = max(0, $total - $paid);
        $this->collection_status = billCollectionStatus($total, $paid);
        $this->save();
    }
}
