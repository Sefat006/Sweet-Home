<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class FlatTenant extends Model
{
   use HasFactory, SoftDeletes;
 
    protected $fillable = [
        'flat_id', 'tenant_id', 'start_date', 'end_date', 'advance_amount',
        'advance_document', 'agreement_document', 'police_form_document',
        'notice_document', 'house_rent_copy', 'status', 'notes',
    ];
 
    protected $casts = [
        'start_date'     => 'date',
        'end_date'       => 'date',
        'advance_amount' => 'decimal:2',
    ];
 
    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }
 
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
