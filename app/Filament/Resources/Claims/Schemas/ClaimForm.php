<?php

namespace App\Filament\Resources\Claims\Schemas;

use App\Enums\ClaimStatus;
use App\Models\Patient;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClaimForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('app.claims.section.details'))
                    ->schema([
                        Select::make('patient_id')
                            ->label(__('app.appointments.patient'))
                            ->relationship('patient', 'full_name')
                            ->getOptionLabelFromRecordUsing(fn (Patient $record) => $record->mrn.' — '.$record->full_name)
                            ->searchable(['first_name', 'last_name', 'mrn'])
                            ->preload()
                            ->required(),
                        TextInput::make('insurer_name')
                            ->label(__('app.claims.insurer_name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('claim_number')
                            ->label(__('app.claims.claim_number'))
                            ->maxLength(64)
                            ->unique(ignoreRecord: true)
                            ->default(fn () => 'CLM-'.strtoupper(substr(uniqid(), -8))),
                        TextInput::make('amount')
                            ->label(__('app.claims.amount'))
                            ->numeric()
                            ->prefix('₺')
                            ->minValue(0.01)
                            ->required(),
                        Select::make('status')
                            ->label(__('app.appointments.status'))
                            ->options(ClaimStatus::class)
                            ->default(ClaimStatus::Draft->value)
                            ->native(false)
                            ->required(),
                        DatePicker::make('submitted_at')
                            ->label(__('app.claims.submitted_at'))
                            ->displayFormat('d.m.Y'),
                        Textarea::make('notes')
                            ->label(__('app.patient-form.notes'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
