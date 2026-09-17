<?php

namespace App\Filament\Widgets;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

class TodaysAppointmentsWidget extends TableWidget
{
    use InteractsWithTable;

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return Gate::allows('viewAny', Appointment::class);
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
                    ->label(__('app.appointments.provider')),
                TextColumn::make('status')
                    ->label(__('app.appointments.status'))
                    ->badge(),
            ])
            ->paginated(false);
    }

    /**
     * @return Builder<Appointment>
     */
    protected function getTableQuery(): Builder
    {
        return Appointment::query()
            ->onDate(now())
            ->whereNotIn('status', [AppointmentStatus::Cancelled->value])
            ->with(['patient', 'provider']);
    }
}
