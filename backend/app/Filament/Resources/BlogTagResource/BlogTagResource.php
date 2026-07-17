<?php

declare(strict_types=1);

namespace App\Filament\Resources\BlogTagResource;

use App\Filament\Resources\BlogTagResource\Pages\CreateBlogTag;
use App\Filament\Resources\BlogTagResource\Pages\EditBlogTag;
use App\Filament\Resources\BlogTagResource\Pages\ListBlogTags;
use App\Modules\CMS\Models\BlogTag;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class BlogTagResource extends Resource
{
    protected static ?string $model = BlogTag::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return 'heroicon-o-hashtag';
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Blog';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('posts_count')
                    ->counts('posts')
                    ->label('Posts'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBlogTags::route('/'),
            'create' => CreateBlogTag::route('/create'),
            'edit' => EditBlogTag::route('/{record}/edit'),
        ];
    }
}
