<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ScanHistory extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'url',
        'domain',
        'prediction',
        'confidence',
        'indicators',
        'explanation',
        'model_version',
        'processing_time_ms',
        'status',
        'error_code',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'confidence' => 'float',
            'indicators' => 'array',
            'explanation' => 'array',
            'processing_time_ms' => 'integer',
            'completed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ScanHistory $scan) {
            if (empty($scan->uuid)) {
                $scan->uuid = (string) Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
