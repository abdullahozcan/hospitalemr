<?php

namespace App\Filament\Resources\Encounters\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VitalsRelationManager extends RelationManager
{
    protected static string $relationship = 'vitals';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('height_cm')
                    ->label(__('app.vitals.height_cm'))
                    ->numeric()
                    ->minValue(30)
                    ->maxValue(260)
                    ->nullable(),
                TextInput::make('weight_grams')
                    ->label(__('app.vitals.weight'))
                    ->numeric()
                    ->minValue(500)
                    ->maxValue(500000)
                    ->helperText(__('app.vitals.weight_help')),
                TextInput::make('systolic_mmhg')
                    ->label(__('app.vitals.systolic'))
                    ->numeric()
                    ->minValue(50)
                    ->maxValue(250),
                TextInput::make('diastolic_mmhg')
                    ->label(__('app.vitals.diastolic'))
                    ->numeric()
                    ->minValue(30)
                    ->maxValue(150),
                TextInput::make('pulse_bpm')
                    ->label(__('app.vitals.pulse'))
                    ->numeric()
                    ->minValue(30)
                    ->maxValue(250),
                TextInput::make('temperature_c')
                    ->label(__('app.vitals.temperature'))
                    ->numeric()
                    ->minValue(30)
                    ->maxValue(45),
                TextInput::make('spo2_percent')
                    ->label(__('app.vitals.spo2'))
                    ->numeric()
                    ->minValue(50)
                    ->maxValue(100),
                TextInput::make('respiratory_rate')
                    ->label(__('app.vitals.respiratory_rate'))
                    ->numeric()
                    ->minValue(5)
                    ->maxValue(60),
                DatePicker::make('measured_at')
                    ->label(__('app.vitals.measured_at')),
            ])
            ->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('measured_at')
                    ->label(__('app.vitals.measured_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('height_cm')
                    ->label(__('app.vitals.height_cm'))
                    ->suffix(' cm')
                    ->toggleable(),
                TextColumn::make('weight')
                    ->label(__('app.vitals.weight'))
                    ->state(fn ($record) => $record->weight_grams !== null ? number_format($record->weight_grams / 1000, 1).' kg' : null),
                TextColumn::make('blood_pressure')
                    ->label(__('app.vitals.blood_pressure'))
                    ->state(fn ($record) => $record->systolic_mmhg && $record->diastolic_mmhg
                        ? $record->systolic_mmhg.'/'.$record->diastolic_mmhg
                        : null),
                TextColumn::make('pulse_bpm')
                    ->label(__('app.vitals.pulse')),
                TextColumn::make('temperature_c')
                    ->label(__('app.vitals.temperature'))
                    ->suffix(' °C'),
                TextColumn::make('spo2_percent')
                    ->label(__('app.vitals.spo2'))
                    ->suffix(' %'),
                TextColumn::make('bmi')
                    ->label(__('app.vitals.bmi'))
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
