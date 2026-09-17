<?php

namespace App\Filament\Resources\Encounters\Schemas;

use App\Enums\UserRole;
use App\Models\Patient;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class EncounterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('patient_id')
                        ->label(__('app.encounters.patient'))
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
                            fn (Builder $query) => $query->whereHas(
                                'roles',
                                fn (Builder $roleQuery) => $roleQuery->where('name', UserRole::Doctor->value),
                            ),
                        )
                        ->searchable()
                        ->nullable(),
                    DateTimePicker::make('started_at')
                        ->label(__('app.encounters.started_at'))
                        ->required()
                        ->default(now()),
                    DateTimePicker::make('ended_at')
                        ->label(__('app.encounters.ended_at'))
                        ->after('started_at'),
                    TextInput::make('chief_complaint')
                        ->label(__('app.encounters.chief_complaint'))
                        ->maxLength(255)
                        ->columnSpanFull(),
                ]),
                Tabs::make('Soap')->tabs([
                    Tab::make(__('app.encounters.tabs.soap'))
                        ->schema([
                            Textarea::make('subjective')
                                ->label(__('app.encounters.subjective'))
                                ->columnSpanFull()
                                ->autosize(),
                            Textarea::make('objective')
                                ->label(__('app.encounters.objective'))
                                ->columnSpanFull()
                                ->autosize(),
                            Textarea::make('assessment')
                                ->label(__('app.encounters.assessment'))
                                ->columnSpanFull()
                                ->autosize(),
                            Textarea::make('plan')
                                ->label(__('app.encounters.plan'))
                                ->columnSpanFull()
                                ->autosize(),
                        ]),
                    Tab::make(__('app.encounters.tabs.notes'))
                        ->schema([
                            Textarea::make('notes')
                                ->label(__('app.patient-form.notes'))
                                ->columnSpanFull()
                                ->autosize(),
                        ]),
                ])
                    ->columnSpanFull(),
            ]);
    }
}
