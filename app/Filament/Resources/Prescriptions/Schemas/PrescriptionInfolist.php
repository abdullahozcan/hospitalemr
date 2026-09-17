<?php

namespace App\Filament\Resources\Prescriptions\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PrescriptionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('app.prescriptions.section.details'))
                    ->schema([
                        TextEntry::make('patient.mrn')
                            ->label(__('app.patient-form.mrn'))
                            ->badge()
                            ->color('gray')
                            ->url(fn ($record) => $record->patient ? '/admin/patients/'.$record->patient->id : null),
                        TextEntry::make('patient.full_name')
                            ->label(__('app.appointments.patient')),
                        TextEntry::make('prescriber.name')
                            ->label(__('app.prescriptions.prescriber')),
                        TextEntry::make('status')
                            ->label(__('app.appointments.status'))
                            ->badge(),
                        TextEntry::make('issued_at')
                            ->label(__('app.prescriptions.issued_at'))
                            ->dateTime('d.m.Y H:i'),
                        TextEntry::make('dispensed_at')
                            ->label(__('app.prescriptions.dispensed_at'))
                            ->dateTime('d.m.Y H:i'),
                        TextEntry::make('notes')
                            ->label(__('app.patient-form.notes'))
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                Section::make(__('app.prescriptions.items'))
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                TextEntry::make('drug.display_name')
                                    ->label(__('app.prescriptions.drug')),
                                TextEntry::make('dosage')
                                    ->label(__('app.prescriptions.dosage')),
                                TextEntry::make('frequency')
                                    ->label(__('app.prescriptions.frequency')),
                                TextEntry::make('duration_days')
                                    ->label(__('app.prescriptions.duration_days'))
                                    ->suffix(' gün'),
                                TextEntry::make('quantity')
                                    ->label(__('app.prescriptions.quantity')),
                                TextEntry::make('instructions')
                                    ->label(__('app.prescriptions.instructions')),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
