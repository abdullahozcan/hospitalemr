<?php

namespace App\Filament\Resources\ApiClients\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ApiClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('app.api-clients.section.details'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('app.api-clients.name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('client_code')
                            ->label(__('app.api-clients.client_code'))
                            ->maxLength(32)
                            ->unique(ignoreRecord: true)
                            ->default(fn () => 'API-'.strtoupper(substr(uniqid(), -6))),
                        Toggle::make('is_active')
                            ->label(__('app.api-clients.is_active'))
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
