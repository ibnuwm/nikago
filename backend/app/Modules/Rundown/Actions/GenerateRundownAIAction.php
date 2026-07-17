<?php

declare(strict_types=1);

namespace App\Modules\Rundown\Actions;

use App\Core\Base\Action;
use App\Modules\Rundown\Models\Rundown;
use App\Modules\Rundown\Models\RundownItem;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Http\Request;

class GenerateRundownAIAction extends Action
{
    public function execute(mixed ...$params): ?Rundown
    {
        $request = $params[0];
        $user = $request->user();

        $wedding = Wedding::query()
            ->forUser($user->id)
            ->first();

        if (! $wedding) {
            return null;
        }

        $rundown = Rundown::create([
            'tenant_id' => $user->tenant_id ?? 1,
            'wedding_id' => $wedding->id,
            'title' => 'AI Generated Rundown',
            'description' => 'Automatically generated wedding event rundown.',
        ]);

        $items = [
            ['title' => 'Akad Nikah', 'start_time' => '08:00', 'end_time' => '09:00', 'pic' => 'Penghulu', 'sort_order' => 0],
            ['title' => 'Sesi Foto', 'start_time' => '09:00', 'end_time' => '10:00', 'pic' => 'Fotografer', 'sort_order' => 1],
            ['title' => 'Resepsi', 'start_time' => '10:00', 'end_time' => '12:00', 'pic' => 'Wedding Organizer', 'sort_order' => 2],
            ['title' => 'Hiburan', 'start_time' => '12:00', 'end_time' => '13:00', 'pic' => 'MC', 'sort_order' => 3],
            ['title' => 'Sesi Ramah Tamah', 'start_time' => '13:00', 'end_time' => '14:00', 'pic' => 'Keluarga', 'sort_order' => 4],
            ['title' => 'Doa Penutup', 'start_time' => '14:00', 'end_time' => '14:30', 'pic' => 'Penghulu', 'sort_order' => 5],
        ];

        foreach ($items as $itemData) {
            RundownItem::create([
                'rundown_id' => $rundown->id,
                'title' => $itemData['title'],
                'start_time' => $itemData['start_time'],
                'end_time' => $itemData['end_time'],
                'pic' => $itemData['pic'],
                'sort_order' => $itemData['sort_order'],
            ]);
        }

        return $rundown->fresh()->load(['items' => function ($query): void {
            $query->orderBy('sort_order');
        }]);
    }
}
