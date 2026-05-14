<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Flat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'building_id', 'flat_name', 'intercom_number', 'floor', 'status',
        'available_for', 'flat_size', 'flat_details', 'image',
        'house_rent', 'wasa', 'common_electricity', 'gas', 'utility',
        'parking', 'society_bill', 'security', 'other', 'bill_status',
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
    ];

    public function getTotalRentAttribute(): float
    {
        return (float) (
            $this->house_rent +
            $this->wasa +
            $this->common_electricity +
            $this->gas +
            $this->utility +
            $this->parking +
            $this->society_bill +
            $this->security +
            $this->other
        );
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function flatTenants()
    {
        return $this->hasMany(FlatTenant::class);
    }

    public function activeTenant()
    {
        return $this->hasOne(FlatTenant::class)->where('status', 'active');
    }

    // public function monthlyBills()
    // {
    //     return $this->hasMany(MonthlyBill::class);
    // }
}