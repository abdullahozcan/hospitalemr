<?php

namespace App\Filament\Resources\Prescriptions\Schemas;

use App\Enums\PrescriptionStatus;
use App\Models\Drug;
use App\Models\Patient;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class PrescriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('app.prescriptions.section.details'))
                    ->schema([
                        Select::make('patient_id')
                            ->label(__('app.appointments.patient'))
                            ->relationship('patient', 'full_name')
                            ->getOptionLabelFromRecordUsing(fn (Patient $record) => $record->mrn.' — '.$record->full_name)
                            ->searchable(['first_name', 'last_name', 'mrn'])
                            ->preload()
                            ->required(),
                        Select::make('encounter_id')
                            ->label(__('app.encounters.singular'))
                            ->relationship(
                                'encounter',
                                'id',
                                fn (Builder $query) => $query->with('patient')->latest('started_at'),
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn ($record) => $record->patient->mrn.' — '.$record->patient->full_name.' — '.$record->started_at->format('d.m.Y H:i'),
                            )
                            ->searchable()
                            ->nullable(),
                        Select::make('prescriber_id')
                            ->label(__('app.prescriptions.prescriber'))
                            ->relationship(
                                'prescriber',
                                'name',
                                fn (Builder $query) => $query->whereHas('roles', fn (Builder $roleQuery) => $roleQuery->where('name', 'doctor')),
                            )
                            ->searchable()
                            ->nullable(),
                        DateTimePicker::make('issued_at')
                            ->label(__('app.prescriptions.issued_at'))
                            ->default(now())
                            ->required(),
                        Select::make('status')
                            ->label(__('app.appointments.status'))
                            ->options(PrescriptionStatus::class)
                            ->default(PrescriptionStatus::Draft->value)
                            ->native(false)
                            ->required(),
                    ])
                    ->columns(2),
                Section::make(__('app.prescriptions.section.items'))
                    ->schema([
                        Repeater::make('items')
                            ->label(__('app.prescriptions.items'))
                            ->relationship()
                            ->schema([
                                Select::make('drug_id')
                                    ->label(__('app.prescriptions.drug'))
                                    ->relationship('drug', 'name')
                                    ->getOptionLabelFromRecordUsing(fn (Drug $record) => $record->display_name)
                                    ->searchable(['name', 'active_ingredient'])
                                    ->preload()
                                    ->required()
                                    ->distinct(),
                                TextInput::make('dosage')
                                    ->label(__('app.prescriptions.dosage'))
                                    ->maxLength(64),
                                TextInput::make('frequency')
                                    ->label(__('app.prescriptions.frequency'))
                                    ->maxLength(64),
                                TextInput::make('duration_days')
                                    ->label(__('app.prescriptions.duration_days'))
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(365),
                                TextInput::make('quantity')
                                    ->label(__('app.prescriptions.quantity'))
                                    ->numeric()
                                    ->minValue(1),
                                Textarea::make('instructions')
                                    ->label(__('app.prescriptions.instructions'))
                                    ->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ]),
                Section::make(__('app.patient-form.notes'))
                    ->schema([
                        Textarea::make('notes')
                            ->label(__('app.patient-form.notes'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
