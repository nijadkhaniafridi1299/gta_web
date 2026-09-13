<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\GameKey;
use App\Models\Order;
use Filament\Actions\EditAction;
use Filament\Forms\Components\{DateTimePicker, Select, TextInput};
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use BackedEnum;
use UnitEnum;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-shopping-bag';
    protected static string | UnitEnum | null $navigationGroup = 'Financial Management';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Order Information')
                ->schema([
                    TextInput::make('order_number')->readOnly(),
                    Select::make('status')
                        ->options([
                            'pending' => 'Pending Payment',
                            'paid' => 'Paid',
                            'completed' => 'Completed & Delivered',
                            'refunded' => 'Refunded',
                            'cancelled' => 'Cancelled',
                            'failed' => 'Failed',
                        ])
                        ->required(),
                    TextInput::make('customer_name')->required(),
                    TextInput::make('customer_email')->email()->required(),
                ])->columns(2),

            Section::make('Financial Details')
                ->schema([
                    TextInput::make('subtotal')->prefix('$')->numeric()->readOnly(),
                    TextInput::make('discount')->prefix('$')->numeric()->readOnly(),
                    TextInput::make('total')->prefix('$')->numeric()->readOnly(),
                    TextInput::make('currency')->default('USD')->readOnly(),
                    TextInput::make('payment_reference')->label('Payment / Gateway Reference')->readOnly(),
                    DateTimePicker::make('paid_at'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->description(fn(Order $record): string => $record->customer_email),

                TextColumn::make('total')
                    ->label('Amount')
                    ->money('USD')
                    ->weight('bold')
                    ->color('success')
                    ->sortable(),

                TextColumn::make('payment_method_label')
                    ->label('Gateway')
                    ->badge()
                    ->color(fn(string $state): string => match (true) {
                        str_contains($state, 'Crypto') => 'warning',
                        str_contains($state, 'Card') => 'info',
                        str_contains($state, 'PayPal') => 'primary',
                        default => 'gray',
                    }),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'completed', 'paid' => 'success',
                        'pending' => 'warning',
                        'failed', 'cancelled' => 'danger',
                        'refunded' => 'gray',
                        default => 'primary',
                    }),

                TextColumn::make('keys_count')
                    ->label('Keys')
                    ->state(fn(Order $record): int => $record->keys()->count()),

                TextColumn::make('paid_at')
                    ->label('Paid Date')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Ordered')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'completed' => 'Completed',
                        'refunded' => 'Refunded',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                \Filament\Actions\Action::make('restock')
                    ->label('Restock Keys')
                    ->icon('heroicon-m-arrow-path')
                    ->color('warning')
                    ->visible(fn(Order $record) => $record->keys()->count() > 0 && in_array($record->status, ['refunded', 'cancelled']))
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        $count = $record->restockKeys();
                        $record->update(['status' => 'refunded']);

                        Notification::make()
                            ->title("Restocked {$count} Keys")
                            ->body("Keys have been released back to available inventory.")
                            ->success()
                            ->send();
                    }),

                \Filament\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
