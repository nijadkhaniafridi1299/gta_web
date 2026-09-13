<?php

namespace App\Filament\Widgets;

use App\Models\CryptoPayment;
use App\Models\GameKey;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RevenueOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Calculate Total Gross Revenue
        $totalRevenue = (float) Order::whereIn('status', ['paid', 'completed'])->sum('total');

        // Calculate Crypto Revenue
        $cryptoRevenue = (float) CryptoPayment::where('status', 'confirmed')->sum('usd_amount');

        // Total Completed Orders
        $completedOrdersCount = Order::whereIn('status', ['paid', 'completed'])->count();
        $totalOrdersCount = Order::count();

        // Average Order Value
        $aov = $completedOrdersCount > 0 ? ($totalRevenue / $completedOrdersCount) : 0;

        // Inventory Asset Value (Unsold Stock)
        $inventoryValue = (float) GameKey::where('game_keys.status', 'available')
            ->join('products', 'game_keys.product_id', '=', 'products.id')
            ->sum('products.price');

        $availableKeys = GameKey::where('status', 'available')->count();
        $soldKeys = GameKey::where('status', 'sold')->count();

        return [
            Stat::make('Gross Revenue', '$' . number_format($totalRevenue, 2))
                ->description('Total completed sales volume')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([25, 45, 60, 85, 120, 150, (int) $totalRevenue])
                ->color('success'),

            Stat::make('Crypto Revenue', '$' . number_format($cryptoRevenue, 2))
                ->description('BTC, ETH, USDT & Solana payments')
                ->descriptionIcon('heroicon-m-bolt')
                ->chart([10, 20, 35, 45, 65, 80, (int) $cryptoRevenue])
                ->color('warning'),

            Stat::make('Completed Orders', $completedOrdersCount . ' / ' . $totalOrdersCount)
                ->description('Conversion rate: ' . ($totalOrdersCount > 0 ? round(($completedOrdersCount / $totalOrdersCount) * 100) : 100) . '%')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Average Order Value (AOV)', '$' . number_format($aov, 2))
                ->description('Per completed checkout')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'),

            Stat::make('Inventory Asset Value', '$' . number_format($inventoryValue, 2))
                ->description($availableKeys . ' keys in stock (' . $soldKeys . ' sold)')
                ->descriptionIcon('heroicon-m-key')
                ->color('purple'),
        ];
    }
}
