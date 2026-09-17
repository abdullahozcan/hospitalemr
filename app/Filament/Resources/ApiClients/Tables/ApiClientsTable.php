<?php

namespace App\Filament\Resources\ApiClients\Tables;

use App\Models\ApiClient;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ApiClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('app.api-clients.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('client_code')
                    ->label(__('app.api-clients.client_code'))
                    ->badge()
                    ->color('gray'),
                IconColumn::make('is_active')
                    ->label(__('app.api-clients.is_active'))
                    ->boolean(),
                TextColumn::make('last_used_at')
                    ->label(__('app.api-clients.last_used_at'))
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('access_logs_count')
                    ->label(__('app.api-clients.requests_count'))
                    ->counts('accessLogs'),
            ])
            ->recordActions([
                Action::make('generate_token')
                    ->label(__('app.api-clients.generate_token'))
                    ->icon('heroicon-o-key')
                    ->action(function ($record) {
                        $plain = $record->createToken(
                            $record->name,
                            [ApiClient::FHIR_READ_ABILITY],
                        )->plainTextToken;

                        session()->put('api-client-token', $plain);

                        return redirect()->route('api-clients.token');
                    }),
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
