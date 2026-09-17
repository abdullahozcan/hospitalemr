<?php

namespace App\Filament\Resources\Drugs\Pages;

use App\Filament\Resources\Drugs\DrugResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDrug extends ViewRecord
{
    protected static string $resource = DrugResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
