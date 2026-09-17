<?php

namespace App\Filament\Resources\BillingLines\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BillingLinesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('app.billing-lines.billed_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('patient.full_name')
                    ->label(__('app.appointments.patient'))
                    ->searchable(['patients.first_name', 'patients.last_name'])
                    ->description(fn ($record) => $record->patient->mrn),
                TextColumn::make('service.name')
                    ->label(__('app.billing-lines.service'))
                    ->searchable()
                    ->description(fn ($record) => $record->service->code),
                TextColumn::make('quantity')
                    ->label(__('app.billing-lines.quantity'))
                    ->toggleable(),
                TextColumn::make('total')
                    ->label(__('app.billing-lines.total'))
                    ->money('TRY')
                    ->state(fn ($record) => $record->total)
                    ->sortable(false),
                TextColumn::make('claim.claim_number')
                    ->label(__('app.claims.singular'))
                    ->placeholder('—')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('service')
                    ->label(__('app.billing-lines.service'))
                    ->relationship('service', 'name'),
                TernaryFilter::make('encounter_id')
                    ->label(__('app.billing-lines.filters.linked_to_encounter'))
                    ->nullable()
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('encounter_id'),
                        false: fn (Builder $query) => $query->whereNull('encounter_id'),
                    ),
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
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['patient', 'service', 'claim']));
    }
}
