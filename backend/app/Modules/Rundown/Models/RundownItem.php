<?php

declare(strict_types=1);

namespace App\Modules\Rundown\Models;

use Database\Factories\RundownItemFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class RundownItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static ?string $factory = RundownItemFactory::class;

    protected static function newFactory(): Factory
    {
        return RundownItemFactory::new();
    }

    protected $table = 'rundown_items';

    protected $fillable = [
        'uuid',
        'rundown_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'pic',
        'notes',
        'sort_order',
    ];

    protected $attributes = [
        'sort_order' => 0,
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (RundownItem $item): void {
            if (empty($item->uuid)) {
                $item->uuid = (string) Str::uuid();
            }
        });
    }

    public function rundown(): BelongsTo
    {
        return $this->belongsTo(Rundown::class);
    }
}
