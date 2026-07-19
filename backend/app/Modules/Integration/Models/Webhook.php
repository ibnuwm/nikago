<?php

declare(strict_types=1);

namespace App\Modules\Integration\Models;

use App\Modules\Authentication\Models\User;
use Database\Factories\WebhookFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Webhook extends Model
{
    use HasFactory;

    protected static ?string $factory = WebhookFactory::class;

    protected static function newFactory(): Factory
    {
        return WebhookFactory::new();
    }

    protected $table = 'webhooks';

    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'url',
        'secret',
        'events',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'events' => 'array',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Webhook $webhook): void {
            if (empty($webhook->uuid)) {
                $webhook->uuid = (string) Str::uuid();
            }
            if (empty($webhook->secret)) {
                $webhook->secret = Str::random(32);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(WebhookLog::class);
    }
}
