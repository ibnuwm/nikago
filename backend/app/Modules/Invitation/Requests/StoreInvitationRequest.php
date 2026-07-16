<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Requests;

use App\Core\Base\Request;

class StoreInvitationRequest extends Request
{
    public function rules(): array
    {
        return [
            'wedding_id' => ['required', 'string', 'exists:weddings,uuid'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:invitations,slug'],
            'cover_image' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
