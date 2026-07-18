<?php

declare(strict_types=1);

namespace App\Modules\AI\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AiHistory extends Model
{
    protected $table = 'ai_history';

    protected $fillable = [
        'uuid',
        'user_id',
        'feature',
        'prompt',
        'response',
        'model',
        'prompt_tokens',
        'completion_tokens',
    ];

    protected static function booted(): void
    {
        static::creating(function (AiHistory $history): void {
            if (empty($history->uuid)) {
                $history->uuid = (string) Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
