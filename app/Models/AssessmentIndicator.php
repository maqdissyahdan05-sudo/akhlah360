<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentIndicator extends Model
{
    protected $primaryKey = 'indicator_id';

    protected $fillable = ['value_id', 'indicator_statement'];

    public function akhlaqValue(): BelongsTo
    {
        return $this->belongsTo(AkhlaqValue::class, 'value_id', 'value_id');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class, 'indicator_id', 'indicator_id');
    }
}
