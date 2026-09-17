<?php

namespace App\Filament\Resources\LabOrders\Tables;

use App\Enums\LabOrderStatus;
use Filament\Actions\Action;
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
use Illuminate\Support\Facades\Gate;

class LabOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ordered_at')
                    ->label(__('app.lab-orders.ordered_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('patient.full_name')
                    ->label(__('app.appointments.patient'))
                    ->searchable(['patients.first_name', 'patients.last_name'])
                    ->description(fn ($record) => $record->patient->mrn),
                TextColumn::make('orderer.name')
                    ->label(__('app.lab-orders.ordered_by'))
                    ->toggleable(),
                TextColumn::make('status')
                    ->label(__('app.appointments.status'))
                    ->badge(),
                TextColumn::make('results_count')
                    ->label(__('app.lab-orders.results_count'))
                    ->counts('results'),
                TextColumn::make('sample_collected_at')
                    ->label(__('app.lab-orders.sample_collected_at'))
                    ->dateTime('d.m.Y H:i')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('app.appointments.status'))
                    ->options(LabOrderStatus::class),
                Filter::make('pending')
                    ->label(__('app.lab-orders.filters.pending'))
                    ->query(fn (Builder $query) => $query->whereIn('status', [LabOrderStatus::Ordered->value, LabOrderStatus::Collected->value, LabOrderStatus::InLaboratory->value])),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    ...static::statusActions(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('ordered_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['patient', 'orderer']));
    }

    /**
     * Status transition actions gated by role and allowed transitions.
     *
     * @return array<int, Action>
     */
    protected static function statusActions(): array
    {
        return collect(LabOrderStatus::cases())
            ->map(fn (LabOrderStatus $status) => Action::make('lab_'.$status->value)
                ->label(__('app.lab-order-statuses.'.$status->value))
                ->color($status->getColor())
                ->visible(fn ($record) => $record->status !== $status
                    && in_array($status, $record->status->nextStatuses(), true)
                    && Gate::allows(match ($status->value) {
                        LabOrderStatus::Ordered->value => 'update',
                        LabOrderStatus::Collected->value => 'update',
                        LabOrderStatus::InLaboratory->value => 'recordResult',
                        LabOrderStatus::Resulted->value => 'review',
                        LabOrderStatus::Cancelled->value => 'update',
                        default => 'update',
                    }, $record))
                ->action(function ($record) use ($status) {
                    $updates = ['status' => $status];

                    if ($status === LabOrderStatus::Collected) {
                        $updates['sample_collected_at'] = now();
                    }

                    $record->update($updates);
                }))
            ->all();
    }
}
