<?php

namespace App\Filament\Resources\LabOrders\RelationManagers;

use App\Enums\LabResultFlag;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

class ResultsRelationManager extends RelationManager
{
    protected static string $relationship = 'results';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('value_numeric')
                    ->label(__('app.lab-results.value'))
                    ->numeric(),
                TextInput::make('value_text')
                    ->label(__('app.lab-results.value_text'))
                    ->maxLength(255),
                Select::make('flag')
                    ->label(__('app.lab-results.flag'))
                    ->options(LabResultFlag::class)
                    ->native(false),
                Textarea::make('notes')
                    ->label(__('app.patient-form.notes'))
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('lab_test_id')
            ->columns([
                TextColumn::make('labTest.code')
                    ->label(__('app.lab-tests.code'))
                    ->badge()
                    ->color('gray'),
                TextColumn::make('labTest.name')
                    ->label(__('app.lab-tests.name'))
                    ->searchable(),
                TextColumn::make('value_numeric')
                    ->label(__('app.lab-results.value'))
                    ->state(fn ($record) => $record->value_numeric !== null
                        ? $record->value_numeric.' '.($record->labTest->unit ?? '')
                        : ($record->value_text ?? '—')),
                TextColumn::make('flag')
                    ->label(__('app.lab-results.flag'))
                    ->badge()
                    ->placeholder('—'),
                TextColumn::make('resultWriter.name')
                    ->label(__('app.lab-results.resulted_by'))
                    ->toggleable(),
                TextColumn::make('reviewer.name')
                    ->label(__('app.lab-results.reviewed_by'))
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->visible(fn () => Gate::allows('recordResult', $this->getOwnerRecord())),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn ($record) => Gate::allows('update', $record)),
                Action::make('review_result')
                    ->label(__('app.lab-results.review'))
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => $record->resulted_at !== null
                        && $record->reviewed_at === null
                        && Gate::allows('review', $this->getOwnerRecord()))
                    ->action(fn ($record) => $record->update([
                        'reviewed_by' => auth()->id(),
                        'reviewed_at' => now(),
                    ])),
                DeleteAction::make()
                    ->visible(fn ($record) => Gate::allows('delete', $record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['labTest', 'resultWriter', 'reviewer']));
    }
}
