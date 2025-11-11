<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table = 'grades';
    protected $fillable = ['code', 'sla_days'];
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $casts = [
        'id' => 'integer',
        'sla_days' => 'integer'
    ];
}
