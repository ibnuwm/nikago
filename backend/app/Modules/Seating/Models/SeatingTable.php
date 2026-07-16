<?php

declare(strict_types=1);

namespace App\Modules\Seating\Models;

use App\Modules\Guest\Models\Guest;
use App\Modules\Wedding\Models\Wedding;
use Database\Factories\SeatingTableFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SeatingTable extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const SHAPE_ROUND = 'round';
    public const SHAPE_RECTANGLE = 'rectangle';
    public const SHAPE_SQUARE = 'square';

    public const SHAPES = [
        self::SHAPE_ROUND,
        self::SHAPE_RECTANGLE,
        self::SHAPE_SQUARE,
    ];

    protected static ?string $factory = SeatingTableFactory::class;

    protected static function newFactory(): Factory
    {
        return SeatingTableFactory::new();
    }

    protected $table = 'seating_tables';

    protected $fillable = [
        'uuid',
        'tenant_id',
        'wedding_id',
        'name',
        'capacity',
        'shape',
        'position_x',
        'position_y',
        'sort_order',
    ];

    protected $attributes = [
        'capacity' => 8,
        'shape' => self::SHAPE_ROUND,
        'sort_order' => 0,
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'position_x' => 'integer',
            'position_y' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (SeatingTable $table): void {
            if (empty($table->uuid)) {
                $table->uuid = (string) Str::uuid();
            }
        });
    }

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(SeatingAssignment::class, 'table_id');
    }

    public function guests(): BelongsToMany
    {
        return $this->belongsToMany(Guest::class, 'seating_assignments', 'table_id', 'guest_id')
            ->withPivot('seat_number', 'notes')
            ->withTimestamps();
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

    public function getAssignedCount(): int
    {
        return $this->assignments()->count();
    }

    public function getAvailableCapacity(): int
    {
        return $this->capacity - $this->getAssignedCount();
    }

    public function isFull(): bool
    {
        return $this->getAssignedCount() >= $this->capacity;
    }
}
