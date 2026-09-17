<?php

namespace App\Filament\Resources\Appointments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AppointmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('app.appointments.section.appointment'))
                    ->schema([
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
                        TextEntry::make('starts_at')
                            ->label(__('app.appointments.starts_at'))
                            ->dateTime('d.m.Y H:i'),
                        TextEntry::make('ends_at')
                            ->label(__('app.appointments.ends_at'))
                            ->dateTime('d.m.Y H:i'),
                        TextEntry::make('duration')
                            ->label(__('app.appointments.duration'))
                            ->state(fn ($record) => $record->duration.' dk'),
                        TextEntry::make('status')
                            ->label(__('app.appointments.status'))
                            ->badge(),
                        TextEntry::make('room')
                            ->label(__('app.appointments.room')),
                        TextEntry::make('title')
                            ->label(__('app.appointments.title')),
                    ])
                    ->columns(3),
                Section::make(__('app.appointments.section.notes'))
                    ->schema([
                        TextEntry::make('notes')
                            ->label(__('app.appointments.notes')),
                    ]),
            ]);
    }
}
