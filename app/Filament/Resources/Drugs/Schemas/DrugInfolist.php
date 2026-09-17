<?php

namespace App\Filament\Resources\Drugs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DrugInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('app.drugs.section.details'))
                    ->schema([
                        TextEntry::make('code')
                            ->label(__('app.drugs.code'))
                            ->badge()
                            ->color('gray'),
                        TextEntry::make('name')
                            ->label(__('app.drugs.name')),
                        TextEntry::make('active_ingredient')
                            ->label(__('app.drugs.active_ingredient')),
                        TextEntry::make('form')
                            ->label(__('app.drugs.form')),
                        TextEntry::make('strength')
                            ->label(__('app.drugs.strength')),
                        TextEntry::make('unit')
                            ->label(__('app.drugs.unit')),
                        TextEntry::make('is_prescription_only')
                            ->label(__('app.drugs.is_prescription_only'))
                            ->badge(),
                        TextEntry::make('notes')
                            ->label(__('app.patient-form.notes'))
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                Section::make(__('app.drugs.section.stock'))
                    ->schema([
                        TextEntry::make('stock_quantity')
                            ->label(__('app.drugs.stock_quantity'))
                            ->color(fn ($record) => $record->is_low_stock ? 'danger' : null),
                        TextEntry::make('low_stock_threshold')
                            ->label(__('app.drugs.low_stock_threshold')),
                        TextEntry::make('is_low_stock')
                            ->label(__('app.drugs.is_low_stock'))
                            ->badge()
                            ->color(fn ($state) => $state ? 'danger' : 'success'),
                    ])
                    ->columns(3),
            ]);
    }
}
