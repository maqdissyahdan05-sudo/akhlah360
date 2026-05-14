<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    protected $primaryKey = 'employee_id';

    protected $fillable = [
        'employee_name',
        'employee_number',
        'department_id',
        'supervisor_id',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'supervisor_id', 'employee_id');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'supervisor_id', 'employee_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'employee_id', 'employee_id');
    }

    public function assignmentsAsRater(): HasMany
    {
        return $this->hasMany(Assignment::class, 'rater_id', 'employee_id');
    }

    public function assignmentsAsRatee(): HasMany
    {
        return $this->hasMany(Assignment::class, 'ratee_id', 'employee_id');
    }

    public function assessmentResults(): HasMany
    {
        return $this->hasMany(AssessmentResult::class, 'employee_id', 'employee_id');
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class, 'employee_id', 'employee_id');
    }
}
