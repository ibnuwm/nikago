<?php

declare(strict_types=1);

namespace App\Modules\Seating\Actions;

use App\Core\Base\Action;
use App\Modules\Seating\Models\SeatingTable;

class ExportSeatingAction extends Action
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

        $rows = [];
        $rows[] = ['Table Name', 'Capacity', 'Shape', 'Guest Name', 'Seat Number', 'Notes'];

        foreach ($tables as $table) {
            if ($table->assignments->isEmpty()) {
                $rows[] = [$table->name, (string) $table->capacity, $table->shape, '', '', ''];
            } else {
                foreach ($table->assignments as $assignment) {
                    $rows[] = [
                        $table->name,
                        (string) $table->capacity,
                        $table->shape,
                        $assignment->guest?->name ?? '',
                        (string) ($assignment->seat_number ?? ''),
                        $assignment->notes ?? '',
                    ];
                }
            }
        }

        return [
            'data' => $rows,
        ];
    }
}
