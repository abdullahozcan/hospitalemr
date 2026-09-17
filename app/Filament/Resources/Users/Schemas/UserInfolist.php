<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('app.users.section.details'))
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('app.users.name')),
                        TextEntry::make('email')
                            ->label(__('app.users.email')),
                        TextEntry::make('roles.name')
                            ->label(__('app.users.roles'))
                            ->badge()
                            ->color('primary'),
                        TextEntry::make('currentTeam.name')
                            ->label(__('app.users.current_team'))
                            ->placeholder('-'),
                        TextEntry::make('email_verified_at')
                            ->label(__('app.users.verified_at'))
                            ->dateTime('d.m.Y H:i')
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->label(__('app.users.created_at'))
                            ->date('d.m.Y'),
                    ])
                    ->columns(2),
            ]);
    }
}
