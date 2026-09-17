<?php

namespace App\Filament\Resources\Encounters\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EncountersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('started_at')
                    ->label(__('app.encounters.started_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('patient.full_name')
                    ->label(__('app.appointments.patient'))
                    ->searchable(['patients.first_name', 'patients.last_name'])
                    ->description(fn ($record) => $record->patient->mrn),
                TextColumn::make('provider.name')
                    ->label(__('app.appointments.provider'))
                    ->toggleable(),
                TextColumn::make('chief_complaint')
                    ->label(__('app.encounters.chief_complaint'))
                    ->limit(40),
                TextColumn::make('ended_at')
                    ->label(__('app.encounters.ended_at'))
                    ->dateTime('H:i')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('provider')
                    ->label(__('app.appointments.provider'))
                    ->relationship('provider', 'name'),
                SelectFilter::make('facility')
                    ->label(__('app.appointments.facility'))
                    ->relationship('facility', 'name'),
                Filter::make('today')
                    ->label(__('app.appointments.filters.today'))
                    ->query(fn (Builder $query) => $query->whereBetween('started_at', [now()->startOfDay(), now()->endOfDay()])),
                Filter::make('open')
                    ->label(__('app.encounters.filters.open'))
                    ->query(fn (Builder $query) => $query->whereNull('ended_at')),
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
            ->defaultSort('started_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['patient', 'provider']));
    }
}
