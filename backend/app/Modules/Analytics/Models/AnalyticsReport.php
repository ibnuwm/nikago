<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsReport extends Model
{
    protected $table = 'analytics_reports';

    protected $fillable = [
        'type',
        'filters',
        'format',
        'file_path',
        'status',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'filters' => 'json',
        ];
    }
}
