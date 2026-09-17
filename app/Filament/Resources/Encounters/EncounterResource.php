<?php

namespace App\Filament\Resources\Encounters;

use App\Enums\NavigationGroup;
use App\Filament\Resources\Encounters\Pages\CreateEncounter;
use App\Filament\Resources\Encounters\Pages\EditEncounter;
use App\Filament\Resources\Encounters\Pages\ListEncounters;
use App\Filament\Resources\Encounters\Pages\ViewEncounter;
use App\Filament\Resources\Encounters\RelationManagers\DiagnosesRelationManager;
use App\Filament\Resources\Encounters\RelationManagers\VitalsRelationManager;
use App\Filament\Resources\Encounters\Schemas\EncounterForm;
use App\Filament\Resources\Encounters\Schemas\EncounterInfolist;
use App\Filament\Resources\Encounters\Tables\EncountersTable;
use App\Models\Encounter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EncounterResource extends Resource
{
    protected static ?string $model = Encounter::class;

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'chief_complaint';

    public static function getModelLabel(): string
    {
        return __('app.encounters.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.encounters.plural');
    }

    public static function getNavigationGroup(): ?NavigationGroup
    {
        return NavigationGroup::Clinical;
    }

    public static function form(Schema $schema): Schema
    {
        return EncounterForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EncounterInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EncountersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            VitalsRelationManager::class,
            DiagnosesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEncounters::route('/'),
            'create' => CreateEncounter::route('/create'),
            'view' => ViewEncounter::route('/{record}'),
            'edit' => EditEncounter::route('/{record}/edit'),
        ];
    }
}
