<?php

namespace App\Filament\Resources\Patients\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ImmunizationsRelationManager extends RelationManager
{
    protected static string $relationship = 'immunizations';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('vaccine_name')
                    ->label(__('app.immunizations.vaccine_name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('dose_number')
                    ->label(__('app.immunizations.dose_number'))
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(10),
                DateTimePicker::make('administered_at')
                    ->label(__('app.immunizations.administered_at'))
                    ->required(),
                Textarea::make('notes')
                    ->label(__('app.patient-form.notes'))
                    ->columnSpanFull(),
            ])
            ->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('vaccine_name')
            ->columns([
                TextColumn::make('vaccine_name')
                    ->label(__('app.immunizations.vaccine_name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('dose_number')
                    ->label(__('app.immunizations.dose_number')),
                TextColumn::make('administered_at')
                    ->label(__('app.immunizations.administered_at'))
                    ->dateTime('d.m.Y')
                    ->sortable(),
                TextColumn::make('notes')
                    ->label(__('app.patient-form.notes'))
                    ->limit(40)
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
