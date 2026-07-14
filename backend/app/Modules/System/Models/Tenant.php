<?php

declare(strict_types=1);

namespace App\Modules\System\Models;

use App\Core\Base\BaseModel;
use App\Modules\Authentication\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected $table = 'tenants';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'domain',
        'is_active',
        'settings',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    /**
     * Get the users for the tenant.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
