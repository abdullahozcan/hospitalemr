<?php

namespace App\Filament\Resources\BillingLines\Pages;

use App\Filament\Resources\BillingLines\BillingLineResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditBillingLine extends EditRecord
{
    protected static string $resource = BillingLineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
