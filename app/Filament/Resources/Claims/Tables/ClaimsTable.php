<?php

namespace App\Filament\Resources\Claims\Tables;

use App\Enums\ClaimStatus;
use App\Models\Claim;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;

class ClaimsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('claim_number')
                    ->label(__('app.claims.claim_number'))
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                TextColumn::make('patient.full_name')
                    ->label(__('app.appointments.patient'))
                    ->searchable(['patients.first_name', 'patients.last_name'])
                    ->description(fn ($record) => $record->patient->mrn),
                TextColumn::make('insurer_name')
                    ->label(__('app.claims.insurer_name'))
                    ->searchable(),
                TextColumn::make('amount')
                    ->label(__('app.claims.amount'))
                    ->money('TRY')
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('app.appointments.status'))
                    ->badge(),
                TextColumn::make('submitted_at')
                    ->label(__('app.claims.submitted_at'))
                    ->date('d.m.Y')
                    ->toggleable(),
                TextColumn::make('decided_at')
                    ->label(__('app.claims.decided_at'))
                    ->date('d.m.Y')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('app.appointments.status'))
                    ->options(ClaimStatus::class),
                SelectFilter::make('insurer')
                    ->label(__('app.claims.insurer_name'))
                    ->options(fn () => Claim::query()->distinct()->pluck('insurer_name')->filter()->sort()->all()),
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
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn ($query) => $query->with(['patient']));
    }

    /**
     * Status transition actions gated by role and allowed transitions.
     *
     * @return array<int, Action>
     */
    protected static function statusActions(): array
    {
        return collect(ClaimStatus::cases())
            ->map(fn (ClaimStatus $status) => Action::make('claim_'.$status->value)
                ->label(__('app.claim-statuses.'.$status->value))
                ->color($status->getColor())
                ->visible(fn ($record) => $record->status !== $status
                    && in_array($status, $record->status->nextStatuses(), true)
                    && Gate::allows('update', $record))
                ->action(function ($record) use ($status) {
                    $updates = ['status' => $status];

                    if ($status === ClaimStatus::Submitted) {
                        $updates['submitted_at'] = now();
                    }

                    if (in_array($status, [ClaimStatus::Approved, ClaimStatus::Denied], true)) {
                        $updates['decided_at'] = now();
                    }

                    $record->update($updates);
                }))
            ->all();
    }
}
