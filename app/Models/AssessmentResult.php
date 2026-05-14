<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentResult extends Model
{
    protected $primaryKey = 'result_id';

    protected $fillable = [
        'period_id',
        'employee_id',
        'self_score',
        'peer_score',
        'superior_score',
        'subordinate_score',
        'final_score',
        'gap_score',
    ];

    protected $casts = [
        'self_score'        => 'decimal:2',
        'peer_score'        => 'decimal:2',
        'superior_score'    => 'decimal:2',
        'subordinate_score' => 'decimal:2',
        'final_score'       => 'decimal:2',
        'gap_score'         => 'decimal:2',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(AssessmentPeriod::class, 'period_id', 'period_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
