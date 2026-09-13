<?php
namespace App\Filament\Resources;

use App\Models\{GameKey, Product};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\{Select, Textarea};
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\{CreateAction, EditAction, DeleteAction, Action};
use BackedEnum;

class GameKeyResource extends Resource
{
    protected static ?string $model = GameKey::class;
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-key';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('product_id')->relationship('product', 'name')->searchable()->preload()->required(),
            Textarea::make('key')->required()->helperText('Stored encrypted in the database.'),
            Select::make('status')->options([
                'available' => 'Available',
                'reserved' => 'Reserved',
                'sold' => 'Sold',
                'refunded' => 'Refunded',
            ])->default('available')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->label('Product')->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('order.order_number')->label('Order'),
                TextColumn::make('sold_at')->dateTime(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make(),
                Action::make('importKeys')
                    ->label('Bulk Import')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        Select::make('product_id')->options(Product::query()->pluck('name', 'id'))->searchable()->required(),
                        Textarea::make('keys')->label('One key per line')->required()->rows(10),
                    ])
                    ->action(function (array $data) {
                        foreach (preg_split('/\r\n|\r|\n/', $data['keys']) as $key) {
                            $key = trim($key);
                            if ($key) {
                                GameKey::create([
                                    'product_id' => $data['product_id'],
                                    'key' => $key,
                                    'status' => 'available',
                                ]);
                            }
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\GameKeyResource\Pages\ListGameKeys::route('/'),
            'create' => \App\Filament\Resources\GameKeyResource\Pages\CreateGameKey::route('/create'),
            'edit' => \App\Filament\Resources\GameKeyResource\Pages\EditGameKey::route('/{record}/edit'),
        ];
    }
}
