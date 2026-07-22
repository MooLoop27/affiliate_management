<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentHistory extends Model
{
    protected $fillable = [
        'commission_detail_id',
        'user_id',
        'status',
        'date',
        'time',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'string',
    ];

    public function commissionDetail(): BelongsTo
    {
        return $this->belongsTo(CommissionDetail::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

