<?php

declare(strict_types=1);

namespace App\Filament\Resources\AuditLogResource;

use App\Filament\Resources\AuditLogResource\Pages\ListAuditLogs;
use App\Modules\System\Models\AuditLog;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static ?string $recordTitleAttribute = 'id';

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return 'heroicon-o-clipboard-document-list';
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'System';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->sortable(),
                TextColumn::make('event')
                    ->badge()
                    ->sortable(),
                TextColumn::make('auditable_type')
                    ->searchable()
                    ->label('Auditable Type'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->actions([
                ViewAction::make()
                    ->modalHeading('Audit Log Details')
                    ->form([
                        TextInput::make('id'),
                        TextInput::make('user.name'),
                        TextInput::make('event'),
                        TextInput::make('auditable_type'),
                        TextInput::make('auditable_id'),
                        Textarea::make('old_values')
                            ->json(),
                        Textarea::make('new_values')
                            ->json(),
                        TextInput::make('ip_address'),
                        TextInput::make('created_at'),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAuditLogs::route('/'),
        ];
    }
}
