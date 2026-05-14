<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'role_id',
        'employee_id',
        'username',
        'email',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'user_id', 'user_id');
    }

    public function feedbacksCreated(): HasMany
    {
        return $this->hasMany(Feedback::class, 'created_by', 'user_id');
    }

    /**
     * Check if user has a specific role slug.
     */
    public function hasRole(string $roleSlug): bool
    {
        return $this->role?->role_slug === $roleSlug;
    }

    /**
     * Check if user has any of the given role slugs.
     */
    public function hasAnyRole(array $roleSlugs): bool
    {
        return in_array($this->role?->role_slug, $roleSlugs);
    }
}
