<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Actions;

use App\Core\Base\Action;
use Illuminate\Support\Facades\Password;

class ForgotPasswordAction extends Action
{
    public function execute(mixed ...$params): string
    {
        $data = $params[0];

        $status = Password::sendResetLink($data);

        if ($status !== Password::RESET_LINK_SENT) {
            // Always return success message to prevent email enumeration
            return __('If the email exists in our system, a password reset link has been sent.');
        }

        return __($status);
    }
}
