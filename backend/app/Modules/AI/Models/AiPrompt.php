<?php

declare(strict_types=1);

namespace App\Modules\AI\Models;

use Illuminate\Database\Eloquent\Model;

class AiPrompt extends Model
{
    protected $table = 'ai_prompts';

    protected $fillable = [
        'code',
        'name',
        'system_prompt',
        'user_prompt_template',
        'model',
        'temperature',
        'max_tokens',
        'is_active',
    ];

    protected $attributes = [
        'model' => 'openai/gpt-4o-mini',
        'temperature' => 0.7,
        'max_tokens' => 2048,
        'is_active' => true,
    ];

    protected function casts(): array
    {
        return [
            'temperature' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
