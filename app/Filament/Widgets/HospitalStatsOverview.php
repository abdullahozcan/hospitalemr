<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Encounter;
use App\Models\LabOrder;
use App\Models\Patient;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Gate;

class HospitalStatsOverview extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    /**
     * Only visible to users who can view patients.
     */
    public static function canView(): bool
    {
        return Gate::allows('viewAny', Patient::class);
    }

    /**
     * @return array<int, Stat>
     */
    protected function getStats(): array
    {
        $stats = [];

        if (Gate::allows('viewAny', Appointment::class)) {
            $stats[] = Stat::make(
                __('app.widgets.appointments_today'),
                Appointment::query()->whereBetween('starts_at', [now()->startOfDay(), now()->endOfDay()])->count(),
            )
                ->description(__('app.widgets.appointments_today_help'));
        }

        if (Gate::allows('viewAny', Encounter::class)) {
            $stats[] = Stat::make(
                __('app.widgets.open_encounters'),
                Encounter::query()->whereNull('ended_at')->count(),
            )
                ->description(__('app.widgets.open_encounters_help'));
        }

        if (Gate::allows('viewAny', Payment::class)) {
            $todayRevenue = (float) Payment::query()->whereBetween('paid_at', [now()->startOfDay(), now()->endOfDay()])->sum('amount');

            $stats[] = Stat::make(
                __('app.widgets.revenue_today'),
                number_format($todayRevenue, 2).' ₺',
            )
                ->description(__('app.widgets.revenue_help'));
        }

        if (Gate::allows('viewAny', LabOrder::class)) {
            $pendingCount = LabOrder::query()->pending()->count();

            $stats[] = Stat::make(
                __('app.widgets.pending_lab_orders'),
                $pendingCount,
            )
                ->description(__('app.widgets.pending_lab_orders_help'))
                ->color($pendingCount > 0 ? 'warning' : 'success');
        }

        return $stats;
    }
}
