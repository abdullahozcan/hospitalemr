<?php

namespace App\Filament\Pages;

use App\Enums\AppointmentStatus;
use App\Enums\NavigationGroup;
use App\Models\Appointment;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

class FlowBoard extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.flow-board';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function getNavigationLabel(): string
    {
        return __('app.flow-board.singular');
    }

    public static function getNavigationGroup(): ?NavigationGroup
    {
        return NavigationGroup::Patients;
    }

    /**
     * The title shown on the page.
     */
    public function getTitle(): string
    {
        return __('app.flow-board.title');
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('starts_at')
                    ->label(__('app.appointments.starts_at'))
                    ->dateTime('H:i')
                    ->sortable(),
                TextColumn::make('patient.full_name')
                    ->label(__('app.appointments.patient'))
                    ->description(fn ($record) => $record->patient->mrn),
                TextColumn::make('provider.name')
                    ->label(__('app.appointments.provider'))
                    ->toggleable(),
                TextColumn::make('room')
                    ->label(__('app.appointments.room'))
                    ->toggleable(),
                TextColumn::make('status')
                    ->label(__('app.appointments.status'))
                    ->badge(),
            ])
            ->recordActions(collect(AppointmentStatus::cases())
                ->flatMap(fn (AppointmentStatus $status) => [
                    Action::make('flow_'.$status->value)
                        ->label(__('app.appointment-statuses.'.$status->value))
                        ->color($status->getColor())
                        ->visible(fn ($record) => $record->status !== $status
                            && in_array($status, $record->status->nextStatuses(), true)
                            && Gate::allows('update', $record))
                        ->action(fn ($record) => $record->update(['status' => $status])),
                ])
                ->all())
            ->paginated(false)
            ->poll('30s');
    }

    /**
     * Only today's in-flight appointments on the flow board.
     *
     * @return Builder<Appointment>
     */
    protected function getTableQuery(): Builder
    {
        return Appointment::query()
            ->onDate(now())
            ->waiting()
            ->with(['patient', 'provider']);
    }

    public static function canAccess(): bool
    {
        return Gate::allows('viewAny', Appointment::class);
    }
}
