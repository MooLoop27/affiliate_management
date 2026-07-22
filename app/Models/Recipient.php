<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'recipient_code',
        'recipient_name',
        'leader_id',
        'whatsapp',
        'bank_name',
        'bank_account_number',
        'status',
        'notes',
    ];

    public function leader(): BelongsTo
    {
        return $this->belongsTo(Leader::class);
    }

    public function commissionDetails(): HasMany
    {
        return $this->hasMany(CommissionDetail::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public static function generateCode(): string
    {
        $last = self::withTrashed()->latest('id')->first();
        $lastId = $last ? $last->id : 0;
        return 'RCP' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
    }
}

