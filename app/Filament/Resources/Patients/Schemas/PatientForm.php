<?php

namespace App\Filament\Resources\Patients\Schemas;

use App\Enums\Gender;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Patient')->tabs([
                    Tab::make(__('app.patient-form.tabs.demographics'))
                        ->schema([
                            TextInput::make('first_name')
                                ->label(__('app.patient-form.first_name'))
                                ->required()
                                ->maxLength(100),
                            TextInput::make('last_name')
                                ->label(__('app.patient-form.last_name'))
                                ->required()
                                ->maxLength(100),
                            DatePicker::make('birth_date')
                                ->label(__('app.patient-form.birth_date'))
                                ->maxDate(now())
                                ->displayFormat('d.m.Y'),
                            Select::make('gender')
                                ->label(__('app.patient-form.gender'))
                                ->options(Gender::class)
                                ->native(false),
                            TextInput::make('national_id')
                                ->label(__('app.patient-form.national_id'))
                                ->length(11)
                                ->unique(ignoreRecord: true)
                                ->nullable(),
                            Select::make('blood_type')
                                ->label(__('app.patient-form.blood_type'))
                                ->options(__('app.blood_types'))
                                ->native(false),
                        ])
                        ->columns(2),
                    Tab::make(__('app.patient-form.tabs.contact'))
                        ->schema([
                            TextInput::make('phone')
                                ->label(__('app.patient-form.phone'))
                                ->maxLength(32),
                            TextInput::make('email')
                                ->label(__('app.patient-form.email'))
                                ->email()
                                ->maxLength(255),
                            TextInput::make('address')
                                ->label(__('app.patient-form.address'))
                                ->columnSpanFull(),
                            TextInput::make('city')
                                ->label(__('app.patient-form.city'))
                                ->maxLength(100),
                            TextInput::make('district')
                                ->label(__('app.patient-form.district'))
                                ->maxLength(100),
                        ])
                        ->columns(2),
                    Tab::make(__('app.patient-form.tabs.emergency'))
                        ->schema([
                            TextInput::make('emergency_contact_name')
                                ->label(__('app.patient-form.emergency_contact_name'))
                                ->maxLength(200),
                            TextInput::make('emergency_contact_phone')
                                ->label(__('app.patient-form.emergency_contact_phone'))
                                ->maxLength(32),
                            TextInput::make('emergency_contact_relation')
                                ->label(__('app.patient-form.emergency_contact_relation'))
                                ->maxLength(100),
                        ])
                        ->columns(3),
                    Tab::make(__('app.patient-form.tabs.additional'))
                        ->schema([
                            Toggle::make('is_deceased')
                                ->label(__('app.patient-form.is_deceased'))
                                ->live(),
                            DatePicker::make('deceased_at')
                                ->label(__('app.patient-form.deceased_at'))
                                ->visible(fn ($get) => (bool) $get('is_deceased'))
                                ->displayFormat('d.m.Y'),
                            Textarea::make('notes')
                                ->label(__('app.patient-form.notes'))
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                ])
                    ->columnSpanFull(),
            ]);
    }
}
