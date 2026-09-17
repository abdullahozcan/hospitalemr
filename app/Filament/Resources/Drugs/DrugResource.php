<?php

namespace App\Filament\Resources\Drugs;

use App\Enums\NavigationGroup;
use App\Filament\Resources\Drugs\Pages\CreateDrug;
use App\Filament\Resources\Drugs\Pages\EditDrug;
use App\Filament\Resources\Drugs\Pages\ListDrugs;
use App\Filament\Resources\Drugs\Pages\ViewDrug;
use App\Filament\Resources\Drugs\Schemas\DrugForm;
use App\Filament\Resources\Drugs\Schemas\DrugInfolist;
use App\Filament\Resources\Drugs\Tables\DrugsTable;
use App\Models\Drug;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DrugResource extends Resource
{
    protected static ?string $model = Drug::class;

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return __('app.drugs.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.drugs.plural');
    }

    public static function getNavigationGroup(): ?NavigationGroup
    {
        return NavigationGroup::Clinical;
    }

    public static function form(Schema $schema): Schema
    {
        return DrugForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DrugInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DrugsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDrugs::route('/'),
            'create' => CreateDrug::route('/create'),
            'view' => ViewDrug::route('/{record}'),
            'edit' => EditDrug::route('/{record}/edit'),
        ];
    }
}
