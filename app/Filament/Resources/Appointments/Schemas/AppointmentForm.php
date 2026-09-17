<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Enums\AppointmentStatus;
use App\Enums\UserRole;
use App\Models\Patient;
use App\Rules\NoOverlappingAppointment;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('app.appointments.section.appointment'))
                    ->schema([
                        Select::make('patient_id')
                            ->label(__('app.appointments.patient'))
                            ->relationship('patient', 'full_name')
                            ->getOptionLabelFromRecordUsing(fn (Patient $record) => $record->mrn.' — '.$record->full_name)
                            ->searchable(['first_name', 'last_name', 'mrn'])
                            ->preload()
                            ->required(),
                        Select::make('provider_id')
                            ->label(__('app.appointments.provider'))
                            ->relationship(
                                'provider',
                                'name',
                                fn (Builder $query) => $query->whereHas('roles', fn (Builder $roleQuery) => $roleQuery->where('name', UserRole::Doctor->value)),
                            )
                            ->searchable()
                            ->live()
                            ->nullable(),
                        TextInput::make('title')
                            ->label(__('app.appointments.title'))
                            ->maxLength(255),
                        TextInput::make('room')
                            ->label(__('app.appointments.room'))
                            ->maxLength(20),
                        Grid::make(2)
                            ->schema([
                                DateTimePicker::make('starts_at')
                                    ->label(__('app.appointments.starts_at'))
                                    ->required()
                                    ->rule(function ($get, $record) {
                                        return new NoOverlappingAppointment(
                                            $get('provider_id'),
                                            $get('ends_at'),
                                            $record?->id,
                                        );
                                    })
                                    ->live(),
                                DateTimePicker::make('ends_at')
                                    ->label(__('app.appointments.ends_at'))
                                    ->required()
                                    ->after('starts_at'),
                            ]),
                        Select::make('status')
                            ->label(__('app.appointments.status'))
                            ->options(AppointmentStatus::class)
                            ->default(AppointmentStatus::Scheduled->value)
                            ->native(false)
                            ->required(),
                    ])
                    ->columns(2),
                Section::make(__('app.appointments.section.notes'))
                    ->schema([
                        Textarea::make('notes')
                            ->label(__('app.appointments.notes'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
