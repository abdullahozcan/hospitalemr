<?php

namespace App\Filament\Resources\Encounters\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DiagnosesRelationManager extends RelationManager
{
    protected static string $relationship = 'diagnoses';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                TextColumn::make('code')
                    ->label(__('app.diagnoses.code'))
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                TextColumn::make('description')
                    ->label(__('app.diagnoses.description'))
                    ->searchable(),
                IconColumn::make('is_primary')
                    ->label(__('app.diagnoses.is_primary'))
                    ->boolean()
                    ->state(fn ($record) => (bool) $record->pivot->is_primary),
                TextColumn::make('pivot.notes')
                    ->label(__('app.patient-form.notes'))
                    ->limit(40)
                    ->toggleable(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label(__('app.diagnoses.attach'))
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->orderBy('code'))
                    ->schema([
                        Checkbox::make('is_primary')
                            ->label(__('app.diagnoses.is_primary')),
                        Textarea::make('notes')
                            ->label(__('app.patient-form.notes')),
                    ]),
            ])
            ->recordActions([
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
