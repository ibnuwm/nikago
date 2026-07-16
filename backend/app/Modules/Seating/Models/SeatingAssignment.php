<?php

declare(strict_types=1);

namespace App\Modules\Seating\Models;

use App\Modules\Guest\Models\Guest;
use Database\Factories\SeatingAssignmentFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SeatingAssignment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static ?string $factory = SeatingAssignmentFactory::class;

    protected static function newFactory(): Factory
    {
        return SeatingAssignmentFactory::new();
    }

    protected $table = 'seating_assignments';

    protected $fillable = [
        'uuid',
        'tenant_id',
        'table_id',
        'guest_id',
        'seat_number',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'seat_number' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (SeatingAssignment $assignment): void {
            if (empty($assignment->uuid)) {
                $assignment->uuid = (string) Str::uuid();
            }
        });
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(SeatingTable::class, 'table_id');
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }
}
