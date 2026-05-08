<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Manager extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'user_id',
        'manager_id',
        'name',
        'email',
        'phone',
        'password',
        'status',
        'profile_completed',
        'image',
        'present_address',
        'permanent_address',
        'date_of_birth',
        'blood_group',
        'nid_number',
        'nid_document',
        'emergency_contact_name',
        'emergency_contact_phone',
        'occupation_position',
        'occupation_company',
        'occupation_address',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'password'              => 'hashed',
        'date_of_birth'         => 'date',
        'profile_completed'     => 'boolean',
    ];

    // ─── Relationships ─────────────────────────────

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}