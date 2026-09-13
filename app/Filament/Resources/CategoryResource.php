<?php
namespace App\Filament\Resources;

use App\Models\Category;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\{TextInput, Toggle};
use Filament\Tables\Table;
use Filament\Tables\Columns\{TextColumn, IconColumn};
use Filament\Actions\{CreateAction, EditAction, DeleteAction};
use BackedEnum;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-tag';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(100),
            TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Toggle::make('active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('slug'),
                IconColumn::make('active')->boolean(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\CategoryResource\Pages\ListCategories::route('/'),
            'create' => \App\Filament\Resources\CategoryResource\Pages\CreateCategory::route('/create'),
            'edit' => \App\Filament\Resources\CategoryResource\Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
