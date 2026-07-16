<?php

declare(strict_types=1);

namespace App\Modules\Review\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewImage extends Model
{
    protected $table = 'review_images';

    protected $fillable = [
        'review_id',
        'image_url',
        'sort_order',
    ];

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }
}
