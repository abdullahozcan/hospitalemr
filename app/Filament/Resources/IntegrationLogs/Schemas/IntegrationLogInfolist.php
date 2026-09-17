<?php

namespace App\Filament\Resources\IntegrationLogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class IntegrationLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    TextEntry::make('service')
                        ->label(__('app.integration.service'))
                        ->badge()
                        ->formatStateUsing(fn (string $state) => $state === 'sgk' ? 'SGK' : 'e-Nabız'),
                    TextEntry::make('operation')
                        ->label(__('app.integration.operation')),
                    TextEntry::make('status')
                        ->label(__('app.appointments.status'))
                        ->badge()
                        ->color(fn (string $state) => match ($state) {
                            'success' => 'success',
                            'failed' => 'danger',
                            default => 'gray',
                        }),
                    TextEntry::make('code')
                        ->label(__('app.integration.code')),
                    TextEntry::make('message')
                        ->label(__('app.integration.message'))
                        ->columnSpan(2),
                    TextEntry::make('attempts')
                        ->label(__('app.integration.attempts')),
                    TextEntry::make('sent_at')
                        ->label(__('app.integration.sent_at'))
                        ->dateTime('d.m.Y H:i')
                        ->placeholder('—'),
                    TextEntry::make('subject')
                        ->label(__('app.integration.subject'))
                        ->formatStateUsing(fn ($state) => $state ? class_basename($state).' #'.$state->getKey() : '—'),
                ]),
                Section::make(__('app.integration.request'))
                    ->schema([
                        TextEntry::make('request_payload')
                            ->label('')
                            ->state(fn ($record) => $record->request_payload
                                ? json_encode($record->request_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                                : '—'),
                    ]),
                Section::make(__('app.integration.response'))
                    ->schema([
                        TextEntry::make('response_payload')
                            ->label('')
                            ->state(fn ($record) => $record->response_payload
                                ? json_encode($record->response_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                                : '—'),
                    ]),
            ]);
    }
}
