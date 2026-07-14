<?php

declare(strict_types=1);

namespace App\Core\Base;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class BaseModel extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    /**
     * The primary key type.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [];

    /**
     * The attributes that should be hidden by default.
     *
     * @var list<string>
     */
    protected $hidden = [];

    /**
     * Get the name of the unique identifier for the model.
     */
    public function getKeyName(): string
    {
        return 'id';
    }
}
