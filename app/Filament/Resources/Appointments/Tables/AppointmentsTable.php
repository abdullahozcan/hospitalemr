<?php

namespace App\Filament\Resources\Appointments\Tables;

use App\Enums\AppointmentStatus;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

class AppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('starts_at')
                    ->label(__('app.appointments.starts_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->label(__('app.appointments.ends_at'))
                    ->dateTime('H:i')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('patient.full_name')
                    ->label(__('app.appointments.patient'))
                    ->searchable(['patients.first_name', 'patients.last_name'])
                    ->description(fn ($record) => $record->patient->mrn),
                TextColumn::make('provider.name')
                    ->label(__('app.appointments.provider'))
                    ->toggleable(),
                TextColumn::make('status')
                    ->label(__('app.appointments.status'))
                    ->badge(),
                TextColumn::make('room')
                    ->label(__('app.appointments.room'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->label(__('app.appointments.title'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('app.appointments.status'))
                    ->options(AppointmentStatus::class),
                SelectFilter::make('provider')
                    ->label(__('app.appointments.provider'))
                    ->relationship('provider', 'name'),
                Filter::make('today')
                    ->label(__('app.appointments.filters.today'))
                    ->query(fn (Builder $query) => $query->whereBetween('starts_at', [now()->startOfDay(), now()->endOfDay()])),
                Filter::make('upcoming')
                    ->label(__('app.appointments.filters.upcoming'))
                    ->query(fn (Builder $query) => $query->where('starts_at', '>=', now())),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    ...static::statusActions(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('starts_at')
            ->groups([
                'starts_at',
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['patient', 'provider']));
    }

    /**
     * Status transition actions, gated by role and allowed transitions.
     *
     * @return array<int, Action>
     */
    protected static function statusActions(): array
    {
        return collect(AppointmentStatus::cases())
            ->map(fn (AppointmentStatus $status) => Action::make('mark_'.$status->value)
                ->label(__('app.appointment-statuses.'.$status->value))
                ->icon($status->getIcon())
                ->color($status->getColor())
                ->visible(fn ($record) => $record->status !== $status
                    && in_array($status, $record->status->nextStatuses(), true)
                    && Gate::allows('update', $record))
                ->action(fn ($record) => $record->update(['status' => $status])))
            ->all();
    }
}
