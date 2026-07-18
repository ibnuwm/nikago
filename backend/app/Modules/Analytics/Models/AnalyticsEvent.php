<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    protected $table = 'analytics_events';

    protected $fillable = [
        'event_type',
        'user_id',
        'tenant_id',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'json',
        ];
    }
}
