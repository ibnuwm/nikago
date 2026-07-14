<?php

declare(strict_types=1);

namespace App\Modules\Invitation\Models;

use App\Modules\Authentication\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvitationTemplateFavorite extends Model
{
    protected $table = 'invitation_template_favorites';

    protected $fillable = [
        'user_id',
        'template_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(InvitationTemplate::class, 'template_id');
    }
}
