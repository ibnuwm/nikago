<?php

declare(strict_types=1);

namespace App\Modules\RSVP\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RsvpLog extends Model
{
    protected $table = 'rsvp_logs';

    protected $fillable = [
        'rsvp_id',
        'old_status',
        'new_status',
    ];

    public function rsvp(): BelongsTo
    {
        return $this->belongsTo(Rsvp::class);
    }
}
