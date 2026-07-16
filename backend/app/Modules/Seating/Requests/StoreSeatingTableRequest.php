<?php

declare(strict_types=1);

namespace App\Modules\Seating\Requests;

use App\Core\Base\Request;

class StoreSeatingTableRequest extends Request
{
    public function rules(): array
    {
        return [
            'wedding_id' => ['required', 'integer', 'exists:weddings,id'],
            'name' => ['required', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:100'],
            'shape' => ['nullable', 'string', 'in:round,rectangle,square'],
            'position_x' => ['nullable', 'integer'],
            'position_y' => ['nullable', 'integer'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
