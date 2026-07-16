<?php

declare(strict_types=1);

namespace App\Modules\Seating\Actions;

use App\Core\Base\Action;
use App\Modules\Seating\Models\SeatingTable;
use Illuminate\Http\Request;

class PreviewSeatingAction extends Action
{
    public function execute(mixed ...$params): array
    {
        $request = $params[0];
        $user = $request->user();

        $tables = SeatingTable::query()
            ->forUser($user->id)
            ->with(['assignments' => function ($query): void {
                $query->with('guest');
            }])
            ->when($request->query('wedding_id'), function ($query, $weddingId): void {
                $query->forWedding((int) $weddingId);
            })
            ->orderBy('sort_order')
            ->get();

        return [
            'tables' => $tables->map(function (SeatingTable $table): array {
                return [
                    'id' => $table->uuid,
                    'name' => $table->name,
                    'capacity' => $table->capacity,
                    'shape' => $table->shape,
                    'position_x' => $table->position_x,
                    'position_y' => $table->position_y,
                    'assigned_count' => $table->assignments->count(),
                    'guests' => $table->assignments->map(function ($assignment): array {
                        return [
                            'name' => $assignment->guest?->name,
                            'seat_number' => $assignment->seat_number,
                            'notes' => $assignment->notes,
                        ];
                    }),
                ];
            })->toArray(),
            'total_tables' => $tables->count(),
            'total_guests' => $tables->sum(fn ($t) => $t->assignments->count()),
            'total_capacity' => $tables->sum('capacity'),
        ];
    }
}
