<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    protected $primaryKey = 'assignment_id';

    protected $fillable = [
        'period_id',
        'rater_id',
        'ratee_id',
        'relationship_type',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(AssessmentPeriod::class, 'period_id', 'period_id');
    }

    public function rater(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'rater_id', 'employee_id');
    }

    public function ratee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'ratee_id', 'employee_id');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class, 'assignment_id', 'assignment_id');
    }
}
