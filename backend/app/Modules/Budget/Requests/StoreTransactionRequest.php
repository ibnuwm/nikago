<?php

declare(strict_types=1);

namespace App\Modules\Budget\Requests;

use App\Core\Base\Request;
use App\Modules\Budget\Models\BudgetTransaction;
use Illuminate\Validation\Rule;

class StoreTransactionRequest extends Request
{
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:budget_categories,id'],
            'type' => ['required', 'string', Rule::in(BudgetTransaction::TYPES)],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'transaction_date' => ['required', 'date'],
        ];
    }
}
