<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuildingSecurity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'building_id',
        'name',
        'father_name',
        'mother_name',
        'nid_number',
        'nid_document',
        'birth_certificate_number',
        'birth_certificate_document',
        'contact',
        'image',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
