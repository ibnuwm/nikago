<?php

declare(strict_types=1);

namespace App\Modules\Subscription\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpgradeSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'plan_code' => ['required', 'string', 'exists:subscription_plans,code'],
            'billing_period' => ['sometimes', 'required', 'string', 'in:monthly,yearly'],
        ];
    }
}
