<?php

namespace App\Filament\Resources\Patients\RelationManagers;

use App\Enums\AllergySeverity;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AllergiesRelationManager extends RelationManager
{
    protected static string $relationship = 'allergies';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('allergen')
                    ->label(__('app.allergies.allergen'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('reaction')
                    ->label(__('app.allergies.reaction'))
                    ->maxLength(255),
                Select::make('severity')
                    ->label(__('app.allergies.severity'))
                    ->options(AllergySeverity::class)
                    ->native(false),
                DateTimePicker::make('noted_at')
                    ->label(__('app.allergies.noted_at')),
                Textarea::make('notes')
                    ->label(__('app.patient-form.notes'))
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('allergen')
            ->columns([
                TextColumn::make('allergen')
                    ->label(__('app.allergies.allergen'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reaction')
                    ->label(__('app.allergies.reaction'))
                    ->toggleable(),
                TextColumn::make('severity')
                    ->label(__('app.allergies.severity'))
                    ->badge(),
                TextColumn::make('noted_at')
                    ->label(__('app.allergies.noted_at'))
                    ->date('d.m.Y')
                    ->sortable(),
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
