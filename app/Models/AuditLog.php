<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'log_id';

    protected $fillable = [
        'user_id',
        'activity',
        'table_name',
        'record_id',
        'ip_address',
        'old_values',
        'new_values',
        'timestamp',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'timestamp'  => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Static helper to record a log entry.
     */
    public static function record(string $activity, ?string $tableName = null, ?int $recordId = null, array $oldValues = [], array $newValues = []): void
    {
        $userId = auth()->id();

        static::create([
            'user_id'    => $userId,
            'activity'   => $activity,
            'table_name' => $tableName,
            'record_id'  => $recordId,
            'ip_address' => request()->ip(),
            'old_values' => $oldValues ?: null,
            'new_values' => $newValues ?: null,
        ]);
    }
}
