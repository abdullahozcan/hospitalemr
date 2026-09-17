<?php

namespace App\Filament\Resources\Services\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label(__('app.services.code'))
                    ->badge()
                    ->color('gray')
                    ->toggleable(),
                TextColumn::make('name')
                    ->label(__('app.services.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->label(__('app.services.price'))
                    ->money('TRY')
                    ->sortable(),
                TextColumn::make('billing_lines_count')
                    ->label(__('app.services.times_billed'))
                    ->counts('billingLines')
                    ->toggleable(isToggledHiddenByDefault: true),
                ToggleColumn::make('is_active')
                    ->label(__('app.services.is_active')),
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
