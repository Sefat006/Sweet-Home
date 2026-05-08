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
         'role', 'admin_id',
         'name', 'email', 'phone', 'password',
         'status', 'approved_at', 'profile_completed',
         'image', 'present_address', 'permanent_address',
         'date_of_birth', 'blood_group', 'marital_status',
         'nid_number', 'nid_document',
         'passport_number', 'passport_expiry', 'passport_document',
         'tin_number', 'tin_document',
         'driving_licence_number', 'driving_licence_expiry', 'driving_licence_document',
         'emergency_contact',
         'occupation_position', 'occupation_company', 'occupation_address', 'occupation_document',
     ];
 
     protected $hidden = ['password', 'remember_token'];
 
     protected $casts = [
         'password'          => 'hashed',
         'approved_at'       => 'datetime',
         'date_of_birth'     => 'date',
         'passport_expiry'   => 'date',
         'driving_licence_expiry' => 'date',
         'emergency_contact' => 'array', // JSON column
         'profile_completed' => 'boolean',
     ];
 
     public function managers()
     {
         return $this->hasMany(Manager::class, 'user_id');
     }
 
}
