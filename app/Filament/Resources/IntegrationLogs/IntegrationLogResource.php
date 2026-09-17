<?php

namespace App\Filament\Resources\IntegrationLogs;

use App\Enums\NavigationGroup;
use App\Filament\Resources\IntegrationLogs\Pages\ListIntegrationLogs;
use App\Filament\Resources\IntegrationLogs\Pages\ViewIntegrationLog;
use App\Filament\Resources\IntegrationLogs\Schemas\IntegrationLogInfolist;
use App\Filament\Resources\IntegrationLogs\Tables\IntegrationLogsTable;
use App\Models\IntegrationLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class IntegrationLogResource extends Resource
{
    protected static ?string $model = IntegrationLog::class;

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSignal;

    protected static ?string $recordTitleAttribute = 'operation';

    public static function getModelLabel(): string
    {
        return __('app.integration.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.integration.plural');
    }

    public static function getNavigationGroup(): ?NavigationGroup
    {
        return NavigationGroup::Settings;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return IntegrationLogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IntegrationLogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIntegrationLogs::route('/'),
            'view' => ViewIntegrationLog::route('/{record}'),
        ];
    }
}
