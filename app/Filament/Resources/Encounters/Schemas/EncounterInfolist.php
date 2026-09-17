<?php

namespace App\Filament\Resources\Encounters\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EncounterInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    TextEntry::make('patient.mrn')
                        ->label(__('app.patient-form.mrn'))
                        ->badge()
                        ->color('gray')
                        ->url(fn ($record) => $record->patient ? '/admin/patients/'.$record->patient->id : null),
                    TextEntry::make('patient.full_name')
                        ->label(__('app.appointments.patient')),
                    TextEntry::make('provider.name')
                        ->label(__('app.appointments.provider')),
                    TextEntry::make('facility.name')
                        ->label(__('app.appointments.facility')),
                    TextEntry::make('started_at')
                        ->label(__('app.encounters.started_at'))
                        ->dateTime('d.m.Y H:i'),
                    TextEntry::make('ended_at')
                        ->label(__('app.encounters.ended_at'))
                        ->dateTime('d.m.Y H:i'),
                    TextEntry::make('chief_complaint')
                        ->label(__('app.encounters.chief_complaint'))
                        ->columnSpanFull(),
                ]),
                Section::make(__('app.encounters.tabs.soap'))
                    ->schema([
                        TextEntry::make('subjective')
                            ->label(__('app.encounters.subjective')),
                        TextEntry::make('objective')
                            ->label(__('app.encounters.objective')),
                        TextEntry::make('assessment')
                            ->label(__('app.encounters.assessment')),
                        TextEntry::make('plan')
                            ->label(__('app.encounters.plan')),
                    ]),
                Section::make(__('app.encounters.tabs.notes'))
                    ->schema([
                        TextEntry::make('notes')
                            ->label(__('app.patient-form.notes')),
                    ]),
                Section::make(__('app.billing-lines.summary'))
                    ->schema([
                        RepeatableEntry::make('billingLines')
                            ->label('')
                            ->schema([
                                TextEntry::make('service.name')
                                    ->label(__('app.billing-lines.service')),
                                TextEntry::make('quantity')
                                    ->label(__('app.billing-lines.quantity')),
                                TextEntry::make('total')
                                    ->label(__('app.billing-lines.total'))
                                    ->money('TRY'),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                        TextEntry::make('billing_total')
                            ->label(__('app.billing-lines.total'))
                            ->money('TRY')
                            ->state(fn ($record) => $record->billingLines->sum('total'))
                            ->color('primary'),
                    ]),
            ]);
    }
}
