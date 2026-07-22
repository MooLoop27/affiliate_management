<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transaction_code',
        'date',
        'singapore_partner_id',
        'leader_id',
        'company_balance_amount',
        'sg_commission_percentage',
        'leader_commission_percentage',
        'total_commission',
        'sg_commission_amount',
        'leader_commission_amount',
        'recipient_total_commission',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function singaporePartner(): BelongsTo
    {
        return $this->belongsTo(SingaporePartner::class);
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(Leader::class);
    }

    public function commissionDetails(): HasMany
    {
        return $this->hasMany(CommissionDetail::class);
    }

    public static function generateCode(): string
    {
        $last = self::withTrashed()->latest('id')->first();
        $lastId = $last ? $last->id : 0;
        return 'TRX' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
    }

    public function calculateCommissions(): void
    {
        $settings = Setting::pluck('value', 'key');

        $sgPercentage = $this->sg_commission_percentage ?: ($settings['sg_commission_percentage'] ?? 5);
        $leaderPercentage = $this->leader_commission_percentage ?: ($settings['leader_commission_percentage'] ?? 10);

        $this->sg_commission_percentage = $sgPercentage;
        $this->leader_commission_percentage = $leaderPercentage;

        $totalCommission = $this->company_balance_amount * ($sgPercentage + $leaderPercentage) / 100;
        $this->sg_commission_amount = $this->company_balance_amount * $sgPercentage / 100;
        $this->leader_commission_amount = $this->company_balance_amount * $leaderPercentage / 100;
        $this->total_commission = $totalCommission;

        $recipientTotal = $this->commissionDetails->sum('commission_amount');
        $this->recipient_total_commission = $recipientTotal;
    }
}

