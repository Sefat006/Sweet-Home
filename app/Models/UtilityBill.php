<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UtilityBill extends Model
{
    use HasFactory, SoftDeletes;
 
    protected $fillable = [
        'building_id', 'bill_type', 'billing_name',
        'bill_month', 'bill_year', 'invoice_number', 'due_date',
        'total_amount', 'paid_amount', 'remaining_amount',
        'payment_status', 'payment_date', 'payment_method',
        'transaction_reference', 'document', 'notes', 'created_by',
    ];
 
    protected $casts = [
        'total_amount'     => 'decimal:2',
        'paid_amount'      => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'due_date'         => 'date',
        'payment_date'     => 'date',
    ];
 
    public function building()  { return $this->belongsTo(Building::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
 
    public static function billTypeLabel(string $type): string
    {
        return match($type) {
            'wasa'        => 'WASA',
            'titas_gas'   => 'TITAS Gas',
            'holding_tax' => 'Holding Tax',
            'electricity' => 'Common Electricity',
            'other'       => 'Other',
            default       => ucfirst($type),
        };
    }
}