<?php

namespace App\Filament\Resources\Patients\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InsurancePoliciesRelationManager extends RelationManager
{
    protected static string $relationship = 'insurancePolicies';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_name')
                    ->label(__('app.insurance.company_name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('policy_number')
                    ->label(__('app.insurance.policy_number'))
                    ->required()
                    ->maxLength(255),
                DatePicker::make('start_date')
                    ->label(__('app.insurance.start_date'))
                    ->displayFormat('d.m.Y'),
                DatePicker::make('end_date')
                    ->label(__('app.insurance.end_date'))
                    ->displayFormat('d.m.Y')
                    ->after('start_date'),
                Textarea::make('notes')
                    ->label(__('app.insurance.notes'))
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('company_name')
            ->columns([
                TextColumn::make('company_name')
                    ->label(__('app.insurance.company_name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('policy_number')
                    ->label(__('app.insurance.policy_number'))
                    ->searchable()
                    ->badge()
                    ->color('gray'),
                TextColumn::make('start_date')
                    ->label(__('app.insurance.start_date'))
                    ->date('d.m.Y')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label(__('app.insurance.end_date'))
                    ->date('d.m.Y')
                    ->sortable()
                    ->color(fn ($record) => $record->end_date && $record->end_date->isPast() ? 'danger' : null),
                TextColumn::make('notes')
                    ->label(__('app.insurance.notes'))
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
