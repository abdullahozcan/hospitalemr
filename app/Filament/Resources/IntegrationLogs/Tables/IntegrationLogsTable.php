<?php

namespace App\Filament\Resources\IntegrationLogs\Tables;

use App\Enums\UserRole;
use App\Jobs\SyncEncounterToThs;
use App\Jobs\SyncLabOrderToThs;
use App\Jobs\SyncPrescriptionToThs;
use App\Models\Encounter;
use App\Models\IntegrationLog;
use App\Models\LabOrder;
use App\Models\Prescription;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class IntegrationLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('app.integration.sent_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('service')
                    ->label(__('app.integration.service'))
                    ->badge()
                    ->formatStateUsing(fn (string $state) => $state === IntegrationLog::SERVICE_SGK
                        ? 'SGK'
                        : 'e-Nabız')
                    ->color(fn (string $state) => $state === IntegrationLog::SERVICE_SGK ? 'warning' : 'info'),
                TextColumn::make('operation')
                    ->label(__('app.integration.operation'))
                    ->badge()
                    ->color('gray'),
                TextColumn::make('subject_type')
                    ->label(__('app.integration.subject'))
                    ->formatStateUsing(fn (string $state, $record) => match ($state) {
                        'encounter' => __('app.encounters.singular').' #'.$record->subject_id,
                        'lab_order' => __('app.lab-orders.singular').' #'.$record->subject_id,
                        'prescription' => __('app.prescriptions.singular').' #'.$record->subject_id,
                        default => $state.' #'.$record->subject_id,
                    }),
                TextColumn::make('status')
                    ->label(__('app.appointments.status'))
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        IntegrationLog::STATUS_SUCCESS => 'success',
                        IntegrationLog::STATUS_FAILED => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        IntegrationLog::STATUS_SUCCESS => __('app.integration.statuses.success'),
                        IntegrationLog::STATUS_FAILED => __('app.integration.statuses.failed'),
                        default => __('app.integration.statuses.pending'),
                    }),
                TextColumn::make('code')
                    ->label(__('app.integration.code'))
                    ->badge()
                    ->color('gray')
                    ->toggleable(),
                TextColumn::make('message')
                    ->label(__('app.integration.message'))
                    ->limit(50)
                    ->toggleable(),
                TextColumn::make('attempts')
                    ->label(__('app.integration.attempts'))
                    ->numeric()
                    ->alignEnd(),
            ])
            ->filters([
                SelectFilter::make('service')
                    ->label(__('app.integration.service'))
                    ->options([
                        IntegrationLog::SERVICE_ENABIZ => 'e-Nabız',
                        IntegrationLog::SERVICE_SGK => 'SGK',
                    ]),
                SelectFilter::make('status')
                    ->label(__('app.appointments.status'))
                    ->options([
                        IntegrationLog::STATUS_SUCCESS => __('app.integration.statuses.success'),
                        IntegrationLog::STATUS_FAILED => __('app.integration.statuses.failed'),
                        IntegrationLog::STATUS_PENDING => __('app.integration.statuses.pending'),
                    ]),
            ])
            ->recordActions([
                Action::make('retry')
                    ->label(__('app.integration.retry'))
                    ->icon('heroicon-o-arrow-path')
                    ->visible(fn ($record) => $record->status === IntegrationLog::STATUS_FAILED
                        && auth()->user()?->hasRole(UserRole::Admin->value))
                    ->action(function (IntegrationLog $record) {
                        $subject = $record->subject;

                        if ($subject === null) {
                            return;
                        }

                        if ($record->operation === 'sendEncounter' && $subject instanceof Encounter) {
                            SyncEncounterToThs::dispatch($subject);

                            return;
                        }

                        if ($record->operation === 'sendLabResults' && $subject instanceof LabOrder) {
                            SyncLabOrderToThs::dispatch($subject);

                            return;
                        }

                        if ($record->operation === 'sendPrescription' && $subject instanceof Prescription) {
                            SyncPrescriptionToThs::dispatch($subject);
                        }
                    }),
                ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
