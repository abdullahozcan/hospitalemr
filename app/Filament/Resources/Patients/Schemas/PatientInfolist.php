<?php

namespace App\Filament\Resources\Patients\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PatientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('app.patient-form.tabs.demographics'))
                    ->schema([
                        TextEntry::make('mrn')
                            ->label(__('app.patient-form.mrn'))
                            ->badge()
                            ->color('gray'),
                        TextEntry::make('full_name')
                            ->label(__('app.patient-form.full_name')),
                        TextEntry::make('birth_date')
                            ->label(__('app.patient-form.birth_date'))
                            ->date('d.m.Y'),
                        TextEntry::make('age')
                            ->label(__('app.patient-form.age'))
                            ->state(fn ($record) => $record->age !== null ? $record->age.' '.__('app.age_unit.years') : null),
                        TextEntry::make('gender')
                            ->label(__('app.patient-form.gender'))
                            ->badge(),
                        TextEntry::make('national_id')
                            ->label(__('app.patient-form.national_id')),
                        TextEntry::make('blood_type')
                            ->label(__('app.patient-form.blood_type')),
                    ])
                    ->columns(3),
                Section::make(__('app.patient-form.tabs.contact'))
                    ->schema([
                        TextEntry::make('phone')
                            ->label(__('app.patient-form.phone')),
                        TextEntry::make('email')
                            ->label(__('app.patient-form.email')),
                        TextEntry::make('address')
                            ->label(__('app.patient-form.address')),
                        TextEntry::make('city')
                            ->label(__('app.patient-form.city')),
                        TextEntry::make('district')
                            ->label(__('app.patient-form.district')),
                    ])
                    ->columns(3),
                Section::make(__('app.patient-form.tabs.emergency'))
                    ->schema([
                        TextEntry::make('emergency_contact_name')
                            ->label(__('app.patient-form.emergency_contact_name')),
                        TextEntry::make('emergency_contact_phone')
                            ->label(__('app.patient-form.emergency_contact_phone')),
                        TextEntry::make('emergency_contact_relation')
                            ->label(__('app.patient-form.emergency_contact_relation')),
                    ])
                    ->columns(3),
                Section::make(__('app.patient-form.tabs.additional'))
                    ->schema([
                        TextEntry::make('is_deceased')
                            ->label(__('app.patient-form.is_deceased'))
                            ->badge()
                            ->color(fn ($state) => $state ? 'danger' : 'success'),
                        TextEntry::make('deceased_at')
                            ->label(__('app.patient-form.deceased_at'))
                            ->date('d.m.Y'),
                        TextEntry::make('notes')
                            ->label(__('app.patient-form.notes')),
                    ])
                    ->columns(3),
            ]);
    }
}
