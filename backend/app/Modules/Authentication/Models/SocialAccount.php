<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialAccount extends Model
{
    protected $table = 'social_accounts';

    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'access_token',
        'refresh_token',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'expired_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
