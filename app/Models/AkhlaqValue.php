<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AkhlaqValue extends Model
{
    protected $primaryKey = 'value_id';

    protected $fillable = ['value_name', 'description'];

    public function indicators(): HasMany
    {
        return $this->hasMany(AssessmentIndicator::class, 'value_id', 'value_id');
    }
}
