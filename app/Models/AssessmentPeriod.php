<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentPeriod extends Model
{
    protected $primaryKey = 'period_id';

    protected $fillable = [
        'period_name',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'period_id', 'period_id');
    }

    public function assessmentResults(): HasMany
    {
        return $this->hasMany(AssessmentResult::class, 'period_id', 'period_id');
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class, 'period_id', 'period_id');
    }

    /**
     * Get total number of assignments in this period.
     */
    public function getTotalAssignmentsAttribute(): int
    {
        return $this->assignments()->count();
    }

    /**
     * Get number of completed assignments in this period.
     */
    public function getCompletedAssignmentsAttribute(): int
    {
        return $this->assignments()->where('is_completed', true)->count();
    }

    /**
     * Get completion percentage of this period.
     */
    public function getCompletionPercentageAttribute(): float
    {
        $total = $this->total_assignments;
        if ($total === 0) return 0;
        return round(($this->completed_assignments / $total) * 100, 1);
    }
}
