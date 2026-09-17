<?php

namespace App\Filament\Resources\Payments\Tables;

use App\Enums\PaymentMethod;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('paid_at')
                    ->label(__('app.payments.paid_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('patient.full_name')
                    ->label(__('app.appointments.patient'))
                    ->searchable(['patients.first_name', 'patients.last_name'])
                    ->description(fn ($record) => $record->patient->mrn),
                TextColumn::make('method')
                    ->label(__('app.payments.method'))
                    ->badge()
                    ->icon(fn (PaymentMethod $state) => $state->getIcon()),
                TextColumn::make('amount')
                    ->label(__('app.payments.amount'))
                    ->money('TRY')
                    ->sortable()
                    ->summarize(
                        Sum::make()->money('TRY')->label(__('app.payments.total')),
                    ),
                TextColumn::make('receiver.name')
                    ->label(__('app.payments.received_by'))
                    ->toggleable(),
                TextColumn::make('reference')
                    ->label(__('app.payments.reference'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('method')
                    ->label(__('app.payments.method'))
                    ->options(PaymentMethod::class),
                Filter::make('today')
                    ->label(__('app.appointments.filters.today'))
                    ->query(fn (Builder $query) => $query->whereBetween('paid_at', [now()->startOfDay(), now()->endOfDay()])),
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
            ->defaultSort('paid_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['patient', 'receiver']));
    }
}
