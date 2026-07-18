<?php

declare(strict_types=1);

namespace App\Modules\System\Models;

use App\Modules\Authentication\Models\User;
use Database\Factories\ApiKeyFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    use HasFactory;

    protected static ?string $factory = ApiKeyFactory::class;

    protected static function newFactory(): Factory
    {
        return ApiKeyFactory::new();
    }

    protected $table = 'api_keys';

    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'key',
        'last_used_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'last_used_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ApiKey $apiKey): void {
            if (empty($apiKey->uuid)) {
                $apiKey->uuid = (string) Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
