<?php

declare(strict_types=1);

namespace App\Modules\Budget\Models;

use App\Modules\Wedding\Models\Wedding;
use Database\Factories\BudgetFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Budget extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static ?string $factory = BudgetFactory::class;

    protected static function newFactory(): Factory
    {
        return BudgetFactory::new();
    }

    protected $table = 'budgets';

    protected $fillable = [
        'uuid',
        'tenant_id',
        'wedding_id',
        'title',
        'description',
        'total_budget',
    ];

    protected $attributes = [
        'total_budget' => 0,
    ];

    protected function casts(): array
    {
        return [
            'total_budget' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Budget $budget): void {
            if (empty($budget->uuid)) {
                $budget->uuid = (string) Str::uuid();
            }
        });
    }

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(BudgetCategory::class, 'budget_id');
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
}
