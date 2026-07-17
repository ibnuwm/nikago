<?php

declare(strict_types=1);

namespace App\Modules\Rundown\Requests;

use App\Core\Base\Request;

class UpdateRundownRequest extends Request
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'items' => ['nullable', 'array'],
            'items.*.title' => ['required_with:items', 'string', 'max:255'],
            'items.*.description' => ['nullable', 'string', 'max:5000'],
            'items.*.start_time' => ['nullable', 'string', 'date_format:H:i'],
            'items.*.end_time' => ['nullable', 'string', 'date_format:H:i'],
            'items.*.pic' => ['nullable', 'string', 'max:255'],
            'items.*.notes' => ['nullable', 'string', 'max:5000'],
            'items.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $items = $this->input('items');

            if (! is_array($items)) {
                return;
            }

            foreach ($items as $index => $item) {
                if (isset($item['start_time'], $item['end_time']) && $item['end_time'] <= $item['start_time']) {
                    $validator->errors()->add(
                        "items.{$index}.end_time",
                        'End time must be after start time.'
                    );
                }
            }
        });
    }
}
