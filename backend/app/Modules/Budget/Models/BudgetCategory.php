<?php

declare(strict_types=1);

namespace App\Modules\Budget\Models;

use Database\Factories\BudgetCategoryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BudgetCategory extends Model
{
    use HasFactory;

    protected static ?string $factory = BudgetCategoryFactory::class;

    protected static function newFactory(): Factory
    {
        return BudgetCategoryFactory::new();
    }

    protected $table = 'budget_categories';

    protected $fillable = [
        'uuid',
        'budget_id',
        'name',
        'allocated_amount',
        'sort_order',
    ];

    protected $attributes = [
        'allocated_amount' => 0,
        'sort_order' => 0,
    ];

    protected function casts(): array
    {
        return [
            'allocated_amount' => 'decimal:2',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (BudgetCategory $category): void {
            if (empty($category->uuid)) {
                $category->uuid = (string) Str::uuid();
            }
        });
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(BudgetTransaction::class, 'category_id');
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->whereIn('budget_id', function ($q) use ($userId): void {
            $q->select('id')
                ->from('budgets')
                ->whereIn('wedding_id', function ($q2) use ($userId): void {
                    $q2->select('id')->from('weddings')->where('user_id', $userId);
                });
        });
    }
}
