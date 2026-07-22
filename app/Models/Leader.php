<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Leader extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'leader_code',
        'leader_name',
        'whatsapp',
        'status',
        'notes',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(Recipient::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public static function generateCode(): string
    {
        $last = self::withTrashed()->latest('id')->first();
        $lastId = $last ? $last->id : 0;
        return 'LDR' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
    }
}

