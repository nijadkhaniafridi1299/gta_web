<?php

namespace App\Filament\Resources\CryptoPaymentResource\Pages;

use App\Filament\Resources\CryptoPaymentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCryptoPayment extends EditRecord
{
    protected static string $resource = CryptoPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
