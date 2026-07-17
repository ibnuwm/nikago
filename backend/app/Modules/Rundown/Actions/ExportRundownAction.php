<?php

declare(strict_types=1);

namespace App\Modules\Rundown\Actions;

use App\Core\Base\Action;
use App\Modules\Rundown\Models\Rundown;
use Illuminate\Http\Request;

class ExportRundownAction extends Action
{
    public function execute(mixed ...$params): array
    {
        $request = $params[0];
        $user = $request->user();

        $rundowns = Rundown::query()
            ->forUser($user->id)
            ->with(['items' => function ($query): void {
                $query->orderBy('sort_order');
            }])
            ->when($request->query('wedding_id'), function ($query, $weddingId): void {
                $query->forWedding((int) $weddingId);
            })
            ->orderBy('created_at')
            ->get();

        $rows = [];
        $rows[] = ['Rundown Title', 'Item', 'Start Time', 'End Time', 'PIC', 'Notes', 'Status'];

        foreach ($rundowns as $rundown) {
            if ($rundown->items->isEmpty()) {
                $rows[] = [$rundown->title, '', '', '', '', '', $rundown->status];
            } else {
                foreach ($rundown->items as $item) {
                    $rows[] = [
                        $rundown->title,
                        $item->title,
                        $item->start_time?->format('H:i') ?? '',
                        $item->end_time?->format('H:i') ?? '',
                        $item->pic ?? '',
                        $item->notes ?? '',
                        $rundown->status,
                    ];
                }
            }
        }

        return [
            'data' => $rows,
        ];
    }
}
