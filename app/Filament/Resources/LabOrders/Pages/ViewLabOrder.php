<?php

namespace App\Filament\Resources\LabOrders\Pages;

use App\Filament\Resources\LabOrders\LabOrderResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLabOrder extends ViewRecord
{
    protected static string $resource = LabOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
