<?php

namespace App\Filament\Resources\Drugs\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DrugsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label(__('app.drugs.code'))
                    ->badge()
                    ->color('gray')
                    ->toggleable(),
                TextColumn::make('name')
                    ->label(__('app.drugs.name'))
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->active_ingredient),
                TextColumn::make('form')
                    ->label(__('app.drugs.form'))
                    ->badge()
                    ->color('info'),
                TextColumn::make('strength')
                    ->label(__('app.drugs.strength'))
                    ->toggleable(),
                TextColumn::make('stock_quantity')
                    ->label(__('app.drugs.stock_quantity'))
                    ->numeric()
                    ->sortable()
                    ->color(fn ($record) => $record->is_low_stock ? 'danger' : null),
                TextColumn::make('low_stock_threshold')
                    ->label(__('app.drugs.low_stock_threshold'))
                    ->toggleable(isToggledHiddenByDefault: true),
                ToggleColumn::make('is_prescription_only')
                    ->label(__('app.drugs.is_prescription_only'))
                    ->toggleable(),
            ])
            ->filters([
                TernaryFilter::make('is_prescription_only')
                    ->label(__('app.drugs.is_prescription_only')),
                Filter::make('low_stock')
                    ->label(__('app.drugs.filters.low_stock'))
                    ->query(fn (Builder $query) => $query->whereColumn('stock_quantity', '<=', 'low_stock_threshold')),
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
