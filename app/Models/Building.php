<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// Related models used in relationships
// (Laravel resolves these via namespace if not imported, but explicit imports prevent issues)


class Building extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'logo', 'no_of_floor', 'address',
        'holding_tax_number', 'holding_tax_clearance_up_to', 'holding_tax_document',
        'dolil_document', 'noksha_document', 'mutation_document',
        'khajna_document', 'khajna_clearance_up_to', 'alert_notes',
    ];

    protected $casts = [
        'holding_tax_clearance_up_to' => 'date',
        'khajna_clearance_up_to'      => 'date',
        'no_of_floor'                 => 'integer',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function securities()
    {
        return $this->hasMany(BuildingSecurity::class);
    }

    public function flats()
    {
        return $this->hasMany(Flat::class);
    }

    public function expenses()
    {
        return $this->hasMany(BuildingExpense::class);
    }

    public function monthlyBills()
    {
        return $this->hasMany(MonthlyBill::class);
    }

    public function utilityBills()
    {
        return $this->hasMany(UtilityBill::class);
    }
}