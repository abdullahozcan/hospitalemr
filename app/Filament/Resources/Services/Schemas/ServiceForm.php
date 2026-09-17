<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('app.services.section.details'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('app.services.name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('code')
                            ->label(__('app.services.code'))
                            ->maxLength(32)
                            ->unique(ignoreRecord: true),
                        Textarea::make('description')
                            ->label(__('app.services.description'))
                            ->columnSpanFull(),
                        TextInput::make('price')
                            ->label(__('app.services.price'))
                            ->numeric()
                            ->prefix('₺')
                            ->minValue(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label(__('app.services.is_active'))
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
