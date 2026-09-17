<?php

namespace App\Filament\Resources\LabOrders\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LabOrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('app.lab-orders.section.details'))
                    ->schema([
                        TextEntry::make('patient.mrn')
                            ->label(__('app.patient-form.mrn'))
                            ->badge()
                            ->color('gray')
                            ->url(fn ($record) => $record->patient ? '/admin/patients/'.$record->patient->id : null),
                        TextEntry::make('patient.full_name')
                            ->label(__('app.appointments.patient')),
                        TextEntry::make('orderer.name')
                            ->label(__('app.lab-orders.ordered_by')),
                        TextEntry::make('status')
                            ->label(__('app.appointments.status'))
                            ->badge(),
                        TextEntry::make('ordered_at')
                            ->label(__('app.lab-orders.ordered_at'))
                            ->dateTime('d.m.Y H:i'),
                        TextEntry::make('sample_collected_at')
                            ->label(__('app.lab-orders.sample_collected_at'))
                            ->dateTime('d.m.Y H:i'),
                        TextEntry::make('notes')
                            ->label(__('app.patient-form.notes'))
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                Section::make(__('app.lab-orders.section.results'))
                    ->schema([
                        RepeatableEntry::make('results')
                            ->label('')
                            ->schema([
                                TextEntry::make('labTest.code')
                                    ->label(__('app.lab-tests.code'))
                                    ->badge()
                                    ->color('gray'),
                                TextEntry::make('labTest.name')
                                    ->label(__('app.lab-tests.name')),
                                TextEntry::make('value_numeric')
                                    ->label(__('app.lab-results.value'))
                                    ->state(fn ($record) => $record->value_numeric !== null
                                        ? $record->value_numeric.' '.($record->labTest->unit ?? '')
                                        : ($record->value_text ?? '—')),
                                TextEntry::make('flag')
                                    ->label(__('app.lab-results.flag'))
                                    ->badge()
                                    ->placeholder('—'),
                                TextEntry::make('labTest.reference_range')
                                    ->label(__('app.lab-tests.reference_range'))
                                    ->state(fn ($record) => $record->labTest->reference_min !== null
                                        ? $record->labTest->reference_min.' - '.$record->labTest->reference_max.' '.($record->labTest->unit ?? '')
                                        : '—'),                                TextEntry::make('notes')
                                    ->label(__('app.patient-form.notes')),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
