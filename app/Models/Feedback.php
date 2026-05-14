<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    protected $primaryKey = 'feedback_id';

    protected $fillable = [
        'period_id',
        'employee_id',
        'created_by',
        'feedback_text',
        'development_plan',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(AssessmentPeriod::class, 'period_id', 'period_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }
}
