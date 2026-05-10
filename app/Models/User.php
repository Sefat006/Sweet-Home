<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        // Auth & Role
        'role', 'admin_id', 'name', 'email', 'phone', 'password',
        'status', 'approved_at', 'profile_completed', 'image',

        // Address
        'present_address', 'permanent_address',

        // Personal
        'date_of_birth', 'blood_group', 'marital_status',

        // Education & Occupation
        'education',
        'occupation_position', 'occupation_company',
        'occupation_address', 'occupation_document',

        // ID Documents
        'nid_number', 'nid_document',
        'passport_number', 'passport_expiry', 'passport_document',
        'tin_number', 'tin_document',
        'driving_licence_number', 'driving_licence_expiry', 'driving_licence_document',

        // Emergency Contact (JSON)
        'emergency_contact',

        // Father
        'father_name', 'father_dob', 'father_contact', 'father_email',
        'father_present_address', 'father_permanent_address',
        'father_status', 'father_blood_group', 'father_birth_certificate',
        'father_nid_number', 'father_education', 'father_reminder',
        'father_occupation_position', 'father_occupation_company', 'father_occupation_address',

        // Mother
        'mother_name', 'mother_dob', 'mother_contact', 'mother_email',
        'mother_present_address', 'mother_permanent_address',
        'mother_status', 'mother_expired_date', 'mother_blood_group',
        'mother_birth_certificate', 'mother_nid_number', 'mother_education',
        'mother_reminder', 'mother_occupation_position', 'mother_occupation_company',
        'mother_occupation_address',

        // Spouse & Children
        'no_of_spouse', 'spouse_info',
        'no_of_children', 'children_info',

        // Vehicle
        'no_of_cars', 'car_details', 'car_details_document', 'driver_details',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password'               => 'hashed',
        'approved_at'            => 'datetime',
        'date_of_birth'          => 'date',
        'passport_expiry'        => 'date',
        'driving_licence_expiry' => 'date',
        'father_dob'             => 'date',
        'mother_dob'             => 'date',
        'mother_expired_date'    => 'date',
        'emergency_contact'      => 'array',
        'education'              => 'array',
        'father_education'       => 'array',
        'mother_education'       => 'array',
        'spouse_info'            => 'array',
        'children_info'          => 'array',
        'profile_completed'      => 'boolean',
        'father_reminder'        => 'boolean',
        'mother_reminder'        => 'boolean',
    ];

    public function managers()
    {
        return $this->hasMany(Manager::class, 'user_id');
    }
}