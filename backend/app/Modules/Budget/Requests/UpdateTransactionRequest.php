<?php

declare(strict_types=1);

namespace App\Modules\Budget\Requests;

use App\Core\Base\Request;
use App\Modules\Budget\Models\BudgetTransaction;
use Illuminate\Validation\Rule;

class UpdateTransactionRequest extends Request
{
    public function rules(): array
    {
        return [
            'type' => ['sometimes', 'required', 'string', Rule::in(BudgetTransaction::TYPES)],
            'amount' => ['sometimes', 'required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'transaction_date' => ['sometimes', 'required', 'date'],
        ];
    }
}
