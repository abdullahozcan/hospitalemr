<?php

namespace App\Filament\Resources\Facilities\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class FacilitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label(__('app.facilities.code'))
                    ->badge()
                    ->color('gray')
                    ->toggleable(),
                TextColumn::make('name')
                    ->label(__('app.facilities.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city')
                    ->label(__('app.facilities.city'))
                    ->toggleable(),
                TextColumn::make('phone')
                    ->label(__('app.facilities.phone'))
                    ->toggleable(),
                ToggleColumn::make('is_active')
                    ->label(__('app.facilities.is_active')),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }
}
