<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
     protected $table = 'departemen'; // Sesuaikan dengan nama tabel
    protected $fillable = ['name'];
    public $timestamps = false;
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
