<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrdersWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent Purchases & Deliveries')
            ->query(
                Order::query()->latest()->limit(6)
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->weight('bold')
                    ->copyable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->description(fn(Order $record): string => $record->customer_email),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('USD')
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\TextColumn::make('payment_method_label')
                    ->label('Payment Method')
                    ->badge()
                    ->color(fn(string $state): string => match (true) {
                        str_contains($state, 'Crypto') => 'warning',
                        str_contains($state, 'Card') => 'info',
                        str_contains($state, 'PayPal') => 'primary',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'completed', 'paid' => 'success',
                        'pending' => 'warning',
                        'failed', 'cancelled' => 'danger',
                        'refunded' => 'gray',
                        default => 'primary',
                    }),

                Tables\Columns\TextColumn::make('keys_count')
                    ->label('Keys Dispatched')
                    ->state(fn(Order $record): int => $record->keys()->count()),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                \Filament\Actions\Action::make('view')
                    ->url(fn(Order $record): string => route('filament.admin.resources.orders.edit', $record))
                    ->icon('heroicon-m-eye')
                    ->color('gray'),
            ]);
    }
}
