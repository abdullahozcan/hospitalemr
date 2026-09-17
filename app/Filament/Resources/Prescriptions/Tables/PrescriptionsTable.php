<?php

namespace App\Filament\Resources\Prescriptions\Tables;

use App\Enums\PrescriptionStatus;
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

class PrescriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('issued_at')
                    ->label(__('app.prescriptions.issued_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('patient.full_name')
                    ->label(__('app.appointments.patient'))
                    ->searchable(['patients.first_name', 'patients.last_name'])
                    ->description(fn ($record) => $record->patient->mrn),
                TextColumn::make('prescriber.name')
                    ->label(__('app.prescriptions.prescriber'))
                    ->toggleable(),
                TextColumn::make('status')
                    ->label(__('app.appointments.status'))
                    ->badge(),
                TextColumn::make('items_count')
                    ->label(__('app.prescriptions.items'))
                    ->counts('items'),
                TextColumn::make('dispensed_at')
                    ->label(__('app.prescriptions.dispensed_at'))
                    ->dateTime('d.m.Y H:i')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('app.appointments.status'))
                    ->options(PrescriptionStatus::class),
                SelectFilter::make('prescriber')
                    ->label(__('app.prescriptions.prescriber'))
                    ->relationship('prescriber', 'name'),
                Filter::make('today')
                    ->label(__('app.appointments.filters.today'))
                    ->query(fn (Builder $query) => $query->whereBetween('issued_at', [now()->startOfDay(), now()->endOfDay()])),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    Action::make('dispense')
                        ->label(__('app.prescriptions.dispense'))
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->visible(fn ($record) => $record->status === PrescriptionStatus::Active
                            && Gate::allows('update', $record))
                        ->action(fn ($record) => $record->update([
                            'status' => PrescriptionStatus::Dispensed,
                            'dispensed_at' => now(),
                        ])),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('issued_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['patient', 'prescriber']));
    }
}
