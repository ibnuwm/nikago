<?php

declare(strict_types=1);

namespace App\Core\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasAudit
{
    public static function bootHasAudit(): void
    {
        static::creating(function (Model $model) {
            $model->created_by = auth()->id();
            $model->updated_by = auth()->id();
        });

        static::updating(function (Model $model) {
            $model->updated_by = auth()->id();
        });
    }
}
