<?php

declare(strict_types=1);

namespace App\Modules\Payment\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method_code' => ['required', 'string', 'exists:payment_methods,code'],
            'gateway' => ['required', 'string', 'in:midtrans,xendit'],
        ];
    }
}
