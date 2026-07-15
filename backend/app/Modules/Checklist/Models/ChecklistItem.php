<?php

declare(strict_types=1);

namespace App\Modules\Checklist\Models;

use Database\Factories\ChecklistItemFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ChecklistItem extends Model
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

    protected static ?string $factory = ChecklistItemFactory::class;

    protected static function newFactory(): Factory
    {
        return ChecklistItemFactory::new();
    }

    protected $table = 'checklist_items';

    protected $fillable = [
        'uuid',
        'checklist_id',
        'title',
        'priority',
        'due_date',
        'completed_at',
        'sort_order',
    ];

    protected $attributes = [
        'priority' => self::PRIORITY_MEDIUM,
        'sort_order' => 0,
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed_at' => 'datetime',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ChecklistItem $item): void {
            if (empty($item->uuid)) {
                $item->uuid = (string) Str::uuid();
            }
        });
    }

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(Checklist::class);
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->whereIn('checklist_id', function ($q) use ($userId): void {
            $q->select('id')
                ->from('checklists')
                ->whereIn('wedding_id', function ($q2) use ($userId): void {
                    $q2->select('id')->from('weddings')->where('user_id', $userId);
                });
        });
    }
}
