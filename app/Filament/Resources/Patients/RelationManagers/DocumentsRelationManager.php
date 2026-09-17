<?php

namespace App\Filament\Resources\Patients\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label(__('app.documents.title'))
                    ->required()
                    ->maxLength(255),
                FileUpload::make('file_path')
                    ->label(__('app.documents.file'))
                    ->required()
                    ->disk('public')
                    ->directory('patient-documents')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->maxSize(10240)
                    ->visibility('public')
                    ->downloadable()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->label(__('app.documents.title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('file_path')
                    ->label(__('app.documents.file'))
                    ->formatStateUsing(fn ($state) => basename((string) $state))
                    ->url(fn ($record) => $record->file_path ? Storage::disk('public')->url($record->file_path) : null, shouldOpenInNewTab: true)
                    ->color('primary'),
                TextColumn::make('creator.name')
                    ->label(__('app.documents.uploaded_by'))
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('app.documents.uploaded_at'))
                    ->date('d.m.Y H:i')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
