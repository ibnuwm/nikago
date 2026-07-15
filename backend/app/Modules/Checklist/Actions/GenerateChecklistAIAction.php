<?php

declare(strict_types=1);

namespace App\Modules\Checklist\Actions;

use App\Core\Base\Action;
use App\Modules\Checklist\Models\Checklist;
use App\Modules\Checklist\Models\ChecklistItem;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Http\Request;

class GenerateChecklistAIAction extends Action
{
    public function execute(mixed ...$params): array
    {
        $request = $params[0];
        $user = $request->user();

        $wedding = Wedding::query()
            ->forUser($user->id)
            ->first();

        if (! $wedding) {
            return ['success' => false, 'message' => 'No wedding found.'];
        }

        $checklist = Checklist::create([
            'tenant_id' => $user->tenant_id ?? 1,
            'wedding_id' => $wedding->id,
            'title' => 'AI Generated Checklist',
            'description' => 'Automatically generated checklist for your wedding preparation.',
        ]);

        $tasks = [
            ['title' => 'Book venue', 'priority' => ChecklistItem::PRIORITY_HIGH, 'sort_order' => 0],
            ['title' => 'Choose caterer', 'priority' => ChecklistItem::PRIORITY_HIGH, 'sort_order' => 1],
            ['title' => 'Select wedding dress', 'priority' => ChecklistItem::PRIORITY_HIGH, 'sort_order' => 2],
            ['title' => 'Book photographer', 'priority' => ChecklistItem::PRIORITY_MEDIUM, 'sort_order' => 3],
            ['title' => 'Hire makeup artist', 'priority' => ChecklistItem::PRIORITY_MEDIUM, 'sort_order' => 4],
            ['title' => 'Send invitations', 'priority' => ChecklistItem::PRIORITY_MEDIUM, 'sort_order' => 5],
            ['title' => 'Arrange decorations', 'priority' => ChecklistItem::PRIORITY_MEDIUM, 'sort_order' => 6],
            ['title' => 'Book honeymoon', 'priority' => ChecklistItem::PRIORITY_LOW, 'sort_order' => 7],
            ['title' => 'Schedule rehearsal', 'priority' => ChecklistItem::PRIORITY_LOW, 'sort_order' => 8],
            ['title' => 'Confirm guest list', 'priority' => ChecklistItem::PRIORITY_HIGH, 'sort_order' => 9],
        ];

        foreach ($tasks as $task) {
            ChecklistItem::create([
                'checklist_id' => $checklist->id,
                'title' => $task['title'],
                'priority' => $task['priority'],
                'sort_order' => $task['sort_order'],
            ]);
        }

        return [
            'success' => true,
            'data' => $checklist->fresh()->load(['items' => function ($query): void {
                $query->orderBy('sort_order');
            }]),
        ];
    }
}
