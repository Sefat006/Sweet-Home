<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;
 
    protected $fillable = [
        'tenant_id', 'image', 'name', 'father_name', 'mother_name', 'gender', 'dob',
        'permanent_address', 'phone', 'email', 'blood_group', 'religion', 'nationality',
        'emergency_contact_name', 'emergency_contact_relation', 'emergency_contact_phone', 'emergency_contact_address',
        'marital_status', 'spouse_name', 'spouse_contact_number', 'spouse_father_name', 'spouse_mother_name',
        'spouse_blood_group', 'spouse_date_of_birth',
        'no_of_children', 'children_info',
        'occupation_info', 'education_info',
        'nid_number', 'nid_document',
        'driving_licence_number', 'driving_licence_expiry', 'driving_licence_document',
        'passport_number', 'passport_expiry', 'passport_document',
        'members_info', 'no_of_help', 'help_info', 'no_of_driver', 'driver_info',
        'prev_owner_name', 'prev_owner_phone', 'prev_flat_address', 'prev_leaving_reason',
        'present_address', 'notes'
    ];

    protected $casts = [
        'dob'                      => 'date',
        'spouse_date_of_birth'     => 'date',
        'driving_licence_expiry'   => 'date',
        'passport_expiry'          => 'date',
        'children_info'            => 'array',
        'occupation_info'          => 'array',
        'education_info'           => 'array',
        'nid_document'             => 'array',
        'driving_licence_document' => 'array',
        'passport_document'        => 'array',
        'members_info'             => 'array',
        'help_info'                => 'array',
        'driver_info'              => 'array',
    ];
 
    // All flat assignments (history)
    public function flatTenants()
    {
        return $this->hasMany(FlatTenant::class);
    }
 
    // Current active flat
    public function activeFlat()
    {
        return $this->hasOne(FlatTenant::class)->where('status', 'active')->with('flat');
    }
}
