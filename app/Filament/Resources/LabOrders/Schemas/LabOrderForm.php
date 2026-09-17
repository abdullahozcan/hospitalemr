<?php

namespace App\Filament\Resources\LabOrders\Schemas;

use App\Models\LabTest;
use App\Models\Patient;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class LabOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('app.lab-orders.section.details'))
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
                        DateTimePicker::make('ordered_at')
                            ->label(__('app.lab-orders.ordered_at'))
                            ->default(now())
                            ->required(),
                        CheckboxList::make('lab_test_ids')
                            ->label(__('app.lab-orders.tests'))
                            ->options(fn () => LabTest::query()->active()->orderBy('name')->get()
                                ->mapWithKeys(fn (LabTest $test) => [$test->id => $test->code.' — '.$test->name]))
                            ->searchable()
                            ->bulkToggleable()
                            ->columns(2)
                            ->required(),
                        Textarea::make('notes')
                            ->label(__('app.patient-form.notes'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
