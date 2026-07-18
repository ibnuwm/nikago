<?php

declare(strict_types=1);

namespace App\Modules\CRM\Models;

use App\Modules\Vendor\Models\Vendor;
use Database\Factories\CRM\LeadFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Lead extends Model
{
    use HasFactory;

    protected static ?string $factory = LeadFactory::class;

    protected static function newFactory(): Factory
    {
        return LeadFactory::new();
    }

    protected $table = 'leads';

    protected $fillable = [
        'uuid',
        'tenant_id',
        'vendor_id',
        'user_id',
        'name',
        'email',
        'phone',
        'source',
        'stage',
        'deal_value',
        'notes',
        'assigned_to',
        'closed_at',
    ];

    protected $attributes = [
        'stage' => 'new',
    ];

    protected function casts(): array
    {
        return [
            'deal_value' => 'decimal:2',
            'closed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Lead $lead): void {
            if (empty($lead->uuid)) {
                $lead->uuid = (string) Str::uuid();
            }
        });
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'assigned_to');
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(LeadFollowUp::class, 'lead_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(LeadActivity::class, 'lead_id');
    }

    public function scopeForTenant(Builder $query, int $tenantId): Builder
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeForVendor(Builder $query, int $vendorId): Builder
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeInStage(Builder $query, string $stage): Builder
    {
        return $query->where('stage', $stage);
    }
}
