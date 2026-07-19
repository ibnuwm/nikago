<?php

declare(strict_types=1);

namespace App\Modules\Integration\Models;

use App\Modules\Authentication\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiLog extends Model
{
    protected $table = 'api_logs';

    protected $fillable = [
        'user_id',
        'integration_code',
        'endpoint',
        'method',
        'request_body',
        'response_body',
        'status_code',
        'latency_ms',
        'status',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'request_body' => 'array',
            'response_body' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
