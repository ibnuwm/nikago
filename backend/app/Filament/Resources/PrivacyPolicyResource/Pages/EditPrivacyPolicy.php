<?php

declare(strict_types=1);

namespace App\Filament\Resources\PrivacyPolicyResource\Pages;

use App\Filament\Resources\PrivacyPolicyResource\PrivacyPolicyResource;
use Filament\Resources\Pages\EditRecord;

class EditPrivacyPolicy extends EditRecord
{
    protected static string $resource = PrivacyPolicyResource::class;
}
