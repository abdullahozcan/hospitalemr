<?php

namespace App\Filament\Resources\BillingLines\Schemas;

use App\Models\Patient;
use App\Models\Service;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class BillingLineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('app.billing-lines.section.details'))
                    ->schema([
                        Select::make('patient_id')
                            ->label(__('app.appointments.patient'))
                            ->relationship('patient', 'full_name')
                            ->getOptionLabelFromRecordUsing(fn (Patient $record) => $record->mrn.' — '.$record->full_name)
                            ->searchable(['first_name', 'last_name', 'mrn'])
                            ->preload()
                            ->required(),
                        Select::make('encounter_id')
                            ->label(__('app.encounters.singular'))
                            ->relationship(
                                'encounter',
                                'id',
                                fn (Builder $query) => $query->with('patient')->latest('started_at'),
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn ($record) => $record->patient->mrn.' — '.$record->patient->full_name.' — '.$record->started_at->format('d.m.Y H:i'),
                            )
                            ->searchable()
                            ->nullable(),
                        Select::make('service_id')
                            ->label(__('app.billing-lines.service'))
                            ->options(function ($state) {
                                $query = Service::query()->active();

                                if ($state) {
                                    $query->orWhereKey($state);
                                }

                                return $query->get()->mapWithKeys(fn (Service $service) => [
                                    $service->id => $service->code.' — '.$service->name,
                                ]);
                            })
                            ->searchable()
                            ->live()
                            ->required(),
                        TextInput::make('quantity')
                            ->label(__('app.billing-lines.quantity'))
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(999)
                            ->default(1)
                            ->required(),
                        TextInput::make('unit_price')
                            ->label(__('app.billing-lines.unit_price'))
                            ->numeric()
                            ->prefix('₺')
                            ->minValue(0)
                            ->required()
                            ->hintAction(
                                Action::make('apply_service_price')
                                    ->label(__('app.billing-lines.apply_service_price'))
                                    ->action(function ($get, $set) {
                                        $service = Service::query()
                                            ->whereKey($get('service_id'))
                                            ->first();

                                        if ($service) {
                                            $set('unit_price', $service->price);
                                        }
                                    })
                            ),
                        TextInput::make('discount_amount')
                            ->label(__('app.billing-lines.discount'))
                            ->numeric()
                            ->prefix('₺')
                            ->minValue(0)
                            ->default(0),
                        Textarea::make('notes')
                            ->label(__('app.patient-form.notes'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
