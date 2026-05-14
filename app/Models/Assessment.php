<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assessment extends Model
{
    protected $primaryKey = 'assessment_id';

    protected $fillable = [
        'assignment_id',
        'indicator_id',
        'score',
    ];

    protected $casts = [
        'score' => 'integer',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class, 'assignment_id', 'assignment_id');
    }

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(AssessmentIndicator::class, 'indicator_id', 'indicator_id');
    }
}
