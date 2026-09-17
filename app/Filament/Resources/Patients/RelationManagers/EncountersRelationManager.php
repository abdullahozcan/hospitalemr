<?php

namespace App\Filament\Resources\Patients\RelationManagers;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EncountersRelationManager extends RelationManager
{
    protected static string $relationship = 'encounters';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('started_at')
                    ->label(__('app.encounters.started_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('provider.name')
                    ->label(__('app.appointments.provider'))
                    ->toggleable(),
                TextColumn::make('chief_complaint')
                    ->label(__('app.encounters.chief_complaint'))
                    ->limit(40),
                TextColumn::make('ended_at')
                    ->label(__('app.encounters.ended_at'))
                    ->dateTime('d.m.Y H:i')
                    ->toggleable(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->url(fn ($record) => '/admin/encounters/'.$record->id),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('started_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['provider']));
    }
}
