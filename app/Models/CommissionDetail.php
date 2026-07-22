<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommissionDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transaction_id',
        'recipient_id',
        'commission_percentage',
        'commission_amount',
        'payment_status',
        'transfer_date',
        'transfer_proof',
        'payment_notes',
        'updated_by',
    ];

    protected $casts = [
        'transfer_date' => 'date',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(Recipient::class);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function paymentHistories(): HasMany
    {
        return $this->hasMany(PaymentHistory::class);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }
}

