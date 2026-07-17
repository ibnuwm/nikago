<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Modules\System\Models\AuditLog;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAuditLogsWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AuditLog::query()
                    ->with('user')
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('User'),
                TextColumn::make('event')
                    ->badge(),
                TextColumn::make('auditable_type')
                    ->label('Type')
                    ->words(5),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Date'),
            ]);
    }
}
