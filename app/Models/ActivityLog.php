<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'activity',
        'ip_address',
        'browser',
        'date',
        'time',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(string $activity): void
    {
        self::create([
            'user_id' => auth()->id(),
            'activity' => $activity,
            'ip_address' => request()->ip(),
            'browser' => request()->userAgent(),
            'date' => now(),
            'time' => now()->format('H:i:s'),
        ]);
    }
}

