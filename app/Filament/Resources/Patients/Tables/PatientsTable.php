<?php

namespace App\Filament\Resources\Patients\Tables;

use App\Enums\Gender;
use App\Models\Patient;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PatientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mrn')
                    ->label(__('app.patient-form.mrn'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                TextColumn::make('full_name')
                    ->label(__('app.patient-form.full_name'))
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),
                TextColumn::make('gender')
                    ->label(__('app.patient-form.gender'))
                    ->badge(),
                TextColumn::make('birth_date')
                    ->label(__('app.patient-form.birth_date'))
                    ->date('d.m.Y')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('age')
                    ->label(__('app.patient-form.age'))
                    ->state(fn ($record) => $record->age)
                    ->sortable(false),
                TextColumn::make('phone')
                    ->label(__('app.patient-form.phone'))
                    ->toggleable(),
                TextColumn::make('city')
                    ->label(__('app.patient-form.city'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('insurance_policies_count')
                    ->label(__('app.patient-form.policies'))
                    ->counts('insurancePolicies'),
                TextColumn::make('created_at')
                    ->label(__('app.patient-form.registered_at'))
                    ->date('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('gender')
                    ->label(__('app.patient-form.gender'))
                    ->options(Gender::class),
                TernaryFilter::make('is_deceased')
                    ->label(__('app.patient-form.is_deceased'))
                    ->nullable(),
                SelectFilter::make('blood_type')
                    ->label(__('app.patient-form.blood_type'))
                    ->options(__('app.blood_types')),
                SelectFilter::make('city')
                    ->label(__('app.patient-form.city'))
                    ->options(fn () => collect(Patient::query()->distinct()->pluck('city'))->filter()->sort()->all()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
