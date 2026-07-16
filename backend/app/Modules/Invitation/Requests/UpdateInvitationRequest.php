<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Requests;

use App\Core\Base\Request;

class UpdateInvitationRequest extends Request
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255', 'unique:invitations,slug,' . $this->route('invitation') . ',uuid'],
            'cover_image' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
