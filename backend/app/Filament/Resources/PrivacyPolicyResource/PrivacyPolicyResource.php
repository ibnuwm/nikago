<?php

declare(strict_types=1);

namespace App\Filament\Resources\PrivacyPolicyResource;

use App\Filament\Resources\PrivacyPolicyResource\Pages\EditPrivacyPolicy;
use App\Filament\Resources\PrivacyPolicyResource\Pages\ListPrivacyPolicies;
use App\Modules\CMS\Models\Page;
use BackedEnum;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class PrivacyPolicyResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'CMS';
    }

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return 'heroicon-o-document-text';
    }

    public static function getNavigationLabel(): string
    {
        return 'Privacy Policy';
    }

    public static function getModelLabel(): string
    {
        return 'Privacy Policy';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Privacy Policy';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->byType('privacy_policy');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                RichEditor::make('content')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable(),
                ToggleColumn::make('is_published'),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPrivacyPolicies::route('/'),
            'edit' => EditPrivacyPolicy::route('/{record}/edit'),
        ];
    }
}
