<?php

declare(strict_types=1);

namespace App\Modules\Timeline\Models;

use Database\Factories\TimelineTaskFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TimelineTask extends Model
{
    use HasFactory;

    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';

    public const PRIORITIES = [
        self::PRIORITY_LOW,
        self::PRIORITY_MEDIUM,
        self::PRIORITY_HIGH,
    ];

    protected static ?string $factory = TimelineTaskFactory::class;

    protected static function newFactory(): Factory
    {
        return TimelineTaskFactory::new();
    }

    protected $table = 'timeline_tasks';

    protected $fillable = [
        'uuid',
        'timeline_id',
        'title',
        'description',
        'priority',
        'start_date',
        'due_date',
        'duration_days',
        'completed_at',
        'sort_order',
    ];

    protected $attributes = [
        'priority' => self::PRIORITY_MEDIUM,
        'duration_days' => 1,
        'sort_order' => 0,
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'due_date' => 'date',
            'duration_days' => 'integer',
            'completed_at' => 'datetime',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (TimelineTask $task): void {
            if (empty($task->uuid)) {
                $task->uuid = (string) Str::uuid();
            }
        });
    }

    public function timeline(): BelongsTo
    {
        return $this->belongsTo(Timeline::class);
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->whereIn('timeline_id', function ($q) use ($userId): void {
            $q->select('id')
                ->from('timelines')
                ->whereIn('wedding_id', function ($q2) use ($userId): void {
                    $q2->select('id')->from('weddings')->where('user_id', $userId);
                });
        });
    }
}
