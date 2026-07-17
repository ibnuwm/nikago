<?php

declare(strict_types=1);

namespace App\Filament\Resources\TermsResource\Pages;

use App\Filament\Resources\TermsResource\TermsResource;
use Filament\Resources\Pages\ListRecords;

class ListTerms extends ListRecords
{
    protected static string $resource = TermsResource::class;
}
