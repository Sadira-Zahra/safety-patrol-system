<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finding extends Model
{
    protected $table = 'findings';
    
    protected $fillable = [
        'finding_number',
        'finding_date',
        'location',
        'category_id',
        'grade_id',
        'description',
        'section',
        'image_before',
        'reporter_id',
        'department_id',
        'pic_id',
        'target_date',
        'status',
        'action_plan',
        'completion_date',
        'completion_time',
        'counter_action',
        'action_location',
        'image_after',
        'verified_by',
        'verified_at',
        'closed_by',
        'closed_at',
        'close_note',
        'manager_id',
    ];

    protected $casts = [
        'finding_date' => 'date',
        'target_date' => 'date',
        'completion_date' => 'date',
        'verified_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public $timestamps = true;

    // Relasi
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function department()
    {
        return $this->belongsTo(Departemen::class, 'department_id');
    }

    // Alias untuk konsistensi (dipakai di controller & blade)
    public function departemen()
    {
        return $this->department();
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
    
    // Helper methods
    public function isPending()
    {
        return $this->status === 'PENDING';
    }
    
    public function isInProgress()
    {
        return $this->status === 'IN_PROGRESS';
    }
    
    public function isCompleted()
    {
        return $this->status === 'COMPLETED';
    }
    
    public function isClosed()
    {
        return $this->status === 'CLOSED';
    }
}
