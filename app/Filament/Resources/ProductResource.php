<?php
namespace App\Filament\Resources;

use App\Models\Product;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\{TextInput, Textarea, Select, Toggle};
use Filament\Tables\Table;
use Filament\Tables\Columns\{TextColumn, IconColumn};
use Filament\Actions\{CreateAction, EditAction, DeleteAction};
use BackedEnum;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cube';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required(),
            TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Select::make('category_id')->relationship('category', 'name')->searchable()->preload(),
            Textarea::make('description')->columnSpanFull(),
            TextInput::make('price')->numeric()->prefix('$')->required(),
            Select::make('platform')->options(['PC' => 'PC', 'PlayStation' => 'PlayStation', 'Xbox' => 'Xbox'])->required(),
            TextInput::make('region')->default('Global')->required(),
            TextInput::make('edition'),
            TextInput::make('image'),
            Toggle::make('active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('category.name')->label('Category'),
                TextColumn::make('price')->money('USD'),
                TextColumn::make('platform'),
                TextColumn::make('region'),
                TextColumn::make('available_keys_count')->label('Stock')->state(fn(Product $r) => $r->available_keys_count),
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
            'index' => \App\Filament\Resources\ProductResource\Pages\ListProducts::route('/'),
            'create' => \App\Filament\Resources\ProductResource\Pages\CreateProduct::route('/create'),
            'edit' => \App\Filament\Resources\ProductResource\Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
