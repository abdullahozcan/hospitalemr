<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Enums\PaymentMethod;
use App\Models\Patient;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('app.payments.section.details'))
                    ->schema([
                        Select::make('patient_id')
                            ->label(__('app.appointments.patient'))
                            ->relationship('patient', 'full_name')
                            ->getOptionLabelFromRecordUsing(fn (Patient $record) => $record->mrn.' — '.$record->full_name)
                            ->searchable(['first_name', 'last_name', 'mrn'])
                            ->preload()
                            ->required(),
                        Select::make('method')
                            ->label(__('app.payments.method'))
                            ->options(PaymentMethod::class)
                            ->default(PaymentMethod::Cash->value)
                            ->native(false)
                            ->required(),
                        TextInput::make('amount')
                            ->label(__('app.payments.amount'))
                            ->numeric()
                            ->prefix('₺')
                            ->minValue(0.01)
                            ->required(),
                        DateTimePicker::make('paid_at')
                            ->label(__('app.payments.paid_at'))
                            ->default(now())
                            ->required(),
                        TextInput::make('reference')
                            ->label(__('app.payments.reference'))
                            ->maxLength(64),
                        Textarea::make('notes')
                            ->label(__('app.patient-form.notes'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
