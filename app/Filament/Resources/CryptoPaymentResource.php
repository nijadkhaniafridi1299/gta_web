<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CryptoPaymentResource\Pages;
use App\Models\CryptoPayment;
use App\Services\CryptoPaymentService;
use Filament\Actions\Action;
use Filament\Forms\Components\{DateTimePicker, Select, TextInput};
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Tables\Filters\SelectFilter;
use BackedEnum;
use UnitEnum;

class CryptoPaymentResource extends Resource
{
    protected static ?string $model = CryptoPayment::class;
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-bolt';
    protected static string | UnitEnum | null $navigationGroup = 'Financial Management';
    protected static ?string $navigationLabel = 'Crypto Payments';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Transaction Overview')
                ->schema([
                    TextInput::make('charge_id')->readOnly(),
                    Select::make('currency_code')
                        ->options([
                            'BTC' => 'Bitcoin (BTC)',
                            'ETH' => 'Ethereum (ETH)',
                            'USDT' => 'Tether (USDT)',
                            'USDC' => 'USD Coin (USDC)',
                            'SOL' => 'Solana (SOL)',
                            'DOGE' => 'Dogecoin (DOGE)',
                            'LTC' => 'Litecoin (LTC)',
                        ])->required(),
                    TextInput::make('amount')->numeric()->required(),
                    TextInput::make('usd_amount')->prefix('$')->numeric()->required(),
                    Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'confirmed' => 'Confirmed',
                            'failed' => 'Failed',
                            'expired' => 'Expired',
                        ])->required(),
                    TextInput::make('confirmations')->numeric()->default(0),
                    TextInput::make('wallet_address')->readOnly(),
                    TextInput::make('transaction_hash'),
                    DateTimePicker::make('confirmed_at'),
                    DateTimePicker::make('expires_at'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('charge_id')
                    ->label('Charge ID')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->size('xs'),

                TextColumn::make('order.order_number')
                    ->label('Order #')
                    ->searchable()
                    ->weight('bold')
                    ->url(fn(CryptoPayment $record) => $record->order_id ? route('filament.admin.resources.orders.edit', $record->order_id) : null),

                TextColumn::make('currency_code')
                    ->label('Coin')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'BTC' => 'warning',
                        'ETH' => 'info',
                        'USDT', 'USDC' => 'success',
                        'SOL' => 'purple',
                        'DOGE' => 'amber',
                        default => 'gray',
                    }),

                TextColumn::make('amount')
                    ->label('Crypto Amount')
                    ->fontFamily('mono')
                    ->weight('bold'),

                TextColumn::make('usd_amount')
                    ->label('USD Value')
                    ->money('USD')
                    ->weight('bold')
                    ->color('success'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        'expired' => 'gray',
                        default => 'primary',
                    }),

                TextColumn::make('confirmations')
                    ->label('Blocks')
                    ->formatStateUsing(fn($state) => $state . ' / 3'),

                TextColumn::make('wallet_address')
                    ->label('Deposit Wallet')
                    ->limit(14)
                    ->copyable()
                    ->fontFamily('mono')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('transaction_hash')
                    ->label('TX Hash')
                    ->limit(14)
                    ->copyable()
                    ->fontFamily('mono')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('confirmed_at')
                    ->label('Confirmed')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Initiated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('currency_code')
                    ->options([
                        'BTC' => 'Bitcoin (BTC)',
                        'ETH' => 'Ethereum (ETH)',
                        'USDT' => 'Tether (USDT)',
                        'SOL' => 'Solana (SOL)',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'failed' => 'Failed',
                        'expired' => 'Expired',
                    ]),
            ])
            ->actions([
                Action::make('simulateConfirm')
                    ->label('Force Confirm')
                    ->icon('heroicon-m-check-badge')
                    ->color('success')
                    ->visible(fn(CryptoPayment $record) => $record->status !== 'confirmed')
                    ->requiresConfirmation()
                    ->action(function (CryptoPayment $record) {
                        $service = app(CryptoPaymentService::class);
                        $service->simulateConfirmation($record->charge_id);

                        Notification::make()
                            ->title('Crypto Payment Confirmed!')
                            ->body("Order #{$record->order?->order_number} has been completed and game keys dispatched.")
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCryptoPayments::route('/'),
            'edit' => Pages\EditCryptoPayment::route('/{record}/edit'),
        ];
    }
}
