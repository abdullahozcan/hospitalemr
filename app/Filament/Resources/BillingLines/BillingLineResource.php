<?php

namespace App\Filament\Resources\BillingLines;

use App\Enums\NavigationGroup;
use App\Filament\Resources\BillingLines\Pages\CreateBillingLine;
use App\Filament\Resources\BillingLines\Pages\EditBillingLine;
use App\Filament\Resources\BillingLines\Pages\ListBillingLines;
use App\Filament\Resources\BillingLines\Schemas\BillingLineForm;
use App\Filament\Resources\BillingLines\Tables\BillingLinesTable;
use App\Models\BillingLine;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BillingLineResource extends Resource
{
    protected static ?string $model = BillingLine::class;

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    public static function getModelLabel(): string
    {
        return __('app.billing-lines.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.billing-lines.plural');
    }

    public static function getNavigationGroup(): ?NavigationGroup
    {
        return NavigationGroup::Billing;
    }

    public static function form(Schema $schema): Schema
    {
        return BillingLineForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BillingLinesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBillingLines::route('/'),
            'create' => CreateBillingLine::route('/create'),
            'edit' => EditBillingLine::route('/{record}/edit'),
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
