<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SingaporePartner extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sg_code',
        'partner_name',
        'whatsapp',
        'email',
        'status',
        'notes',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public static function generateCode(): string
    {
        $last = self::withTrashed()->latest('id')->first();
        $lastId = $last ? $last->id : 0;
        return 'SG' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
    }
}

