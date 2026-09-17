<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('app.users.section.details'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('app.users.name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label(__('app.users.email'))
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->label(__('app.users.password'))
                            ->password()
                            ->revealable()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation) => $operation === 'create')
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                        Select::make('roles')
                            ->label(__('app.users.roles'))
                            ->relationship('roles', 'name')
                            ->getOptionLabelFromRecordUsing(
                                fn ($record) => UserRole::tryFrom($record->name)?->getLabel() ?? $record->name,
                            )
                            ->options(collect(UserRole::cases())->mapWithKeys(
                                fn (UserRole $role) => [$role->value => $role->getLabel()],
                            ))
                            ->preload()
                            ->native(false)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
