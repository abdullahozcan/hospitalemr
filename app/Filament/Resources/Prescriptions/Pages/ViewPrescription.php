<?php

namespace App\Filament\Resources\Prescriptions\Pages;

use App\Filament\Resources\Prescriptions\PrescriptionResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPrescription extends ViewRecord
{
    protected static string $resource = PrescriptionResource::class;

    /**
     * Header actions: print the prescription.
     *
     * @return array<int, Action|EditAction|DeleteAction>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->label(__('app.prescriptions.print'))
                ->icon('heroicon-o-printer')
                ->url(route('prescriptions.print', ['prescription' => $this->getRecord()]), shouldOpenInNewTab: true),
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
