<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;
 
    protected $fillable = [
        'tenant_id', 'name', 'image', 'phone', 'email',
        'nid_number', 'nid_document', 'birth_cert_number', 'birth_cert_document',
        'dob', 'blood_group', 'gender', 'marital_status',
        'occupation', 'occupation_document',
        'present_address', 'permanent_address',
        'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relation',
        'previous_rent_info', 'reason_to_change',
        'family_members_count', 'family_members',
        'no_of_children', 'children_info',
        'vehicle_info', 'notes',
        'religion', 'nationality', 'passport_number', 'passport_expiry', 'passport_document',
        'driving_licence_number', 'driving_licence_expiry', 'driving_licence_document',
        'occupation_company', 'occupation_address', 'spouse_name', 'spouse_contact_number',
        'spouse_father_name', 'spouse_mother_name', 'spouse_blood_group', 'spouse_date_of_birth'
    ];
 
    protected $casts = [
        'dob'            => 'date',
        'family_members' => 'array',
        'children_info'  => 'array',
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
