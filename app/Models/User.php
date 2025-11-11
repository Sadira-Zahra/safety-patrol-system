<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'nik',
        'departemen_id',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }
    
    public function isAdministrator()
    {
        return $this->role === 'ADMINISTRATOR';
    }
    
    public function isSafetyPatroller()
    {
        return $this->role === 'SAFETY_PATROLLER';
    }
    
    public function isSafetyAdmin()
    {
        return $this->role === 'SAFETY_ADMIN';
    }
    
    public function isDeptPic()
    {
        return $this->role === 'DEPT_PIC';
    }
    
    public function isDeptManager()
    {
        return $this->role === 'DEPT_MANAGER';
    }
}
