<?php

declare(strict_types=1);

namespace App\Filament\Resources\PrivacyPolicyResource\Pages;

use App\Filament\Resources\PrivacyPolicyResource\PrivacyPolicyResource;
use Filament\Resources\Pages\ListRecords;

class ListPrivacyPolicies extends ListRecords
{
    protected static string $resource = PrivacyPolicyResource::class;
}
