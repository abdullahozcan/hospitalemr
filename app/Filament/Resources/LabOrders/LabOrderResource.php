<?php

namespace App\Filament\Resources\LabOrders;

use App\Enums\NavigationGroup;
use App\Filament\Resources\LabOrders\Pages\CreateLabOrder;
use App\Filament\Resources\LabOrders\Pages\EditLabOrder;
use App\Filament\Resources\LabOrders\Pages\ListLabOrders;
use App\Filament\Resources\LabOrders\Pages\ViewLabOrder;
use App\Filament\Resources\LabOrders\RelationManagers\ResultsRelationManager;
use App\Filament\Resources\LabOrders\Schemas\LabOrderForm;
use App\Filament\Resources\LabOrders\Schemas\LabOrderInfolist;
use App\Filament\Resources\LabOrders\Tables\LabOrdersTable;
use App\Models\LabOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LabOrderResource extends Resource
{
    protected static ?string $model = LabOrder::class;

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static ?string $recordTitleAttribute = 'id';

    public static function getModelLabel(): string
    {
        return __('app.lab-orders.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.lab-orders.plural');
    }

    public static function getNavigationGroup(): ?NavigationGroup
    {
        return NavigationGroup::Laboratory;
    }

    public static function form(Schema $schema): Schema
    {
        return LabOrderForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LabOrderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LabOrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ResultsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLabOrders::route('/'),
            'create' => CreateLabOrder::route('/create'),
            'view' => ViewLabOrder::route('/{record}'),
            'edit' => EditLabOrder::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
