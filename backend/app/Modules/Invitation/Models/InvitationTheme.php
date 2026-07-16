<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Models;

use Illuminate\Database\Eloquent\Model;

class InvitationTheme extends Model
{
    protected $table = 'invitation_themes';

    protected $fillable = [
        'name',
        'primary_color',
        'secondary_color',
        'font_family',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
