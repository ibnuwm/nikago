<?php

declare(strict_types=1);

namespace App\Modules\Notification\Models;

use Database\Factories\Notification\NotificationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Notification extends Model
{
    use HasFactory;

    protected static ?string $factory = NotificationFactory::class;

    protected static function newFactory(): Factory
    {
        return NotificationFactory::new();
    }

    protected $table = 'notifications';

    protected $fillable = [
        'uuid',
        'user_id',
        'type',
        'title',
        'message',
        'channel',
        'is_read',
        'read_at',
        'data',
    ];

    protected $attributes = [
        'is_read' => false,
        'channel' => 'in_app',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
            'data' => 'json',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Notification $notification): void {
            if (empty($notification->uuid)) {
                $notification->uuid = (string) Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }
}
