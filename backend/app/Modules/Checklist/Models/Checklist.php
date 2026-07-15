<?php

declare(strict_types=1);

namespace App\Modules\Checklist\Models;

use App\Modules\Wedding\Models\Wedding;
use Database\Factories\ChecklistFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Checklist extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static ?string $factory = ChecklistFactory::class;

    protected static function newFactory(): Factory
    {
        return ChecklistFactory::new();
    }

    protected $table = 'checklists';

    protected $fillable = [
        'uuid',
        'tenant_id',
        'wedding_id',
        'title',
        'description',
        'progress',
    ];

    protected $attributes = [
        'progress' => 0,
    ];

    protected function casts(): array
    {
        return [
            'progress' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Checklist $checklist): void {
            if (empty($checklist->uuid)) {
                $checklist->uuid = (string) Str::uuid();
            }
        });
    }

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ChecklistItem::class, 'checklist_id');
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->whereIn('wedding_id', function ($q) use ($userId): void {
            $q->select('id')->from('weddings')->where('user_id', $userId);
        });
    }

    public function scopeForWedding(Builder $query, int $weddingId): Builder
    {
        return $query->where('wedding_id', $weddingId);
    }

    public function recalculateProgress(): void
    {
        $total = $this->items()->count();
        $completed = $this->items()->whereNotNull('completed_at')->count();

        $this->update([
            'progress' => $total > 0 ? round(($completed / $total) * 100, 2) : 0,
        ]);
    }
}
