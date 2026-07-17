<?php

declare(strict_types=1);

namespace App\Modules\Budget\Models;

use Database\Factories\BudgetTransactionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BudgetTransaction extends Model
{
    use HasFactory;

    public const TYPE_INCOME = 'income';

    public const TYPE_EXPENSE = 'expense';

    public const TYPES = [
        self::TYPE_INCOME,
        self::TYPE_EXPENSE,
    ];

    protected static ?string $factory = BudgetTransactionFactory::class;

    protected static function newFactory(): Factory
    {
        return BudgetTransactionFactory::new();
    }

    protected $table = 'budget_transactions';

    protected $fillable = [
        'uuid',
        'category_id',
        'type',
        'amount',
        'description',
        'transaction_date',
    ];

    protected $attributes = [
        'type' => self::TYPE_EXPENSE,
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'transaction_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (BudgetTransaction $transaction): void {
            if (empty($transaction->uuid)) {
                $transaction->uuid = (string) Str::uuid();
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BudgetCategory::class, 'category_id');
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->whereIn('category_id', function ($q) use ($userId): void {
            $q->select('id')
                ->from('budget_categories')
                ->whereIn('budget_id', function ($q2) use ($userId): void {
                    $q2->select('id')
                        ->from('budgets')
                        ->whereIn('wedding_id', function ($q3) use ($userId): void {
                            $q3->select('id')->from('weddings')->where('user_id', $userId);
                        });
                });
        });
    }
}
