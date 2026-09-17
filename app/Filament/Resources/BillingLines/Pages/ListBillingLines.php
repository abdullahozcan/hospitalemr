<?php

namespace App\Filament\Resources\BillingLines\Pages;

use App\Filament\Resources\BillingLines\BillingLineResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBillingLines extends ListRecords
{
    protected static string $resource = BillingLineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
