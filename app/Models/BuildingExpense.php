<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 
class BuildingExpense extends Model
{
    use HasFactory, SoftDeletes;
 
    protected $fillable = [
        'building_id', 'expense_month', 'expense_year', 'expense_month_number',
        'security_bill', 'cleaning_bill', 'cleaning_material', 'maintenance',
        'eid_bonus', 'material_replacement', 'flat_cleaning', 'society_cost',
        'driver_cost', 'other', 'total_expense', 'notes', 'created_by',
    ];
 
    protected $casts = [
        'security_bill'       => 'decimal:2',
        'cleaning_bill'       => 'decimal:2',
        'cleaning_material'   => 'decimal:2',
        'maintenance'         => 'decimal:2',
        'eid_bonus'           => 'decimal:2',
        'material_replacement'=> 'decimal:2',
        'flat_cleaning'       => 'decimal:2',
        'society_cost'        => 'decimal:2',
        'driver_cost'         => 'decimal:2',
        'other'               => 'decimal:2',
        'total_expense'       => 'decimal:2',
    ];
 
    public static array $expenseFields = [
        'security_bill'        => 'Security Bill',
        'cleaning_bill'        => 'Cleaning Bill',
        'cleaning_material'    => 'Cleaning Material',
        'maintenance'          => 'Maintenance',
        'eid_bonus'            => 'Eid Bonus',
        'material_replacement' => 'Material Replacement',
        'flat_cleaning'        => 'Flat Cleaning',
        'society_cost'         => 'Society Cost',
        'driver_cost'          => 'Driver Cost',
        'other'                => 'Other',
    ];
 
    public function building()  { return $this->belongsTo(Building::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}