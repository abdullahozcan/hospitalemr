<?php

namespace App\Filament\Resources\Drugs\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DrugForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('app.drugs.section.details'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('app.drugs.name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('code')
                            ->label(__('app.drugs.code'))
                            ->maxLength(32)
                            ->unique(ignoreRecord: true),
                        TextInput::make('active_ingredient')
                            ->label(__('app.drugs.active_ingredient'))
                            ->maxLength(255),
                        TextInput::make('form')
                            ->label(__('app.drugs.form'))
                            ->maxLength(32),
                        TextInput::make('strength')
                            ->label(__('app.drugs.strength'))
                            ->maxLength(32),
                        TextInput::make('unit')
                            ->label(__('app.drugs.unit'))
                            ->maxLength(16),
                        Toggle::make('is_prescription_only')
                            ->label(__('app.drugs.is_prescription_only'))
                            ->default(true),
                    ])
                    ->columns(3),
                Section::make(__('app.drugs.section.stock'))
                    ->schema([
                        TextInput::make('stock_quantity')
                            ->label(__('app.drugs.stock_quantity'))
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required(),
                        TextInput::make('low_stock_threshold')
                            ->label(__('app.drugs.low_stock_threshold'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(1000)
                            ->default(10)
                            ->required(),
                        Textarea::make('notes')
                            ->label(__('app.patient-form.notes'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
