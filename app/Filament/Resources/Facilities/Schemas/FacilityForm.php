<?php

namespace App\Filament\Resources\Facilities\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FacilityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('app.facilities.section.details'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('app.facilities.name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('code')
                            ->label(__('app.facilities.code'))
                            ->maxLength(32),
                        TextInput::make('phone')
                            ->label(__('app.facilities.phone'))
                            ->maxLength(32),
                        TextInput::make('email')
                            ->label(__('app.facilities.email'))
                            ->email()
                            ->maxLength(255),
                        TextInput::make('address')
                            ->label(__('app.facilities.address'))
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('city')
                            ->label(__('app.facilities.city'))
                            ->maxLength(100),
                        TextInput::make('district')
                            ->label(__('app.facilities.district'))
                            ->maxLength(100),
                        TextInput::make('postal_code')
                            ->label(__('app.facilities.postal_code'))
                            ->maxLength(16),
                        Toggle::make('is_active')
                            ->label(__('app.facilities.is_active'))
                            ->default(true),
                    ])
                    ->columns(2),
                Section::make(__('app.facilities.section.notes'))
                    ->schema([
                        Textarea::make('notes')
                            ->label(__('app.patient-form.notes'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
