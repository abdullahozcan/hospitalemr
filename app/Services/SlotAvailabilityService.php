<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\ProviderSchedule;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Computes bookable time slots for a provider.
 *
 * Slots are generated from the provider's weekly schedule in 30 minute
 * increments; taken appointments and past times are excluded.
 */
class SlotAvailabilityService
{
    /**
     * The slot length in minutes.
     */
    protected const SLOT_MINUTES = 30;

    /**
     * Compute the available slots for a doctor on a date.
     *
     * @return Collection<int, array{starts_at: string, ends_at: string, label: string}>
     */
    public function forDoctor(User $doctor, string|Carbon $date): Collection
    {
        $date = Carbon::parse($date);
        $dayOfWeek = $date->dayOfWeekIso; // 1 (Mon) .. 7 (Sun)

        $windows = ProviderSchedule::query()
            ->where('user_id', $doctor->id)
            ->where('day_of_week', $dayOfWeek)
            ->get();

        if ($windows->isEmpty()) {
            return collect();
        }

        $slots = collect();

        foreach ($windows as $window) {
            $cursor = $date->copy()->setTimeFromTimeString($window->starts_at);
            $ends = $date->copy()->setTimeFromTimeString($window->ends_at);

            while ($cursor->copy()->addMinutes(self::SLOT_MINUTES)->lessThanOrEqualTo($ends)) {
                $endsAt = $cursor->copy()->addMinutes(self::SLOT_MINUTES);

                $isPast = $cursor->isBefore(now());
                $isTaken = Appointment::providerHasOverlap($doctor->id, $cursor, $endsAt);

                if (! $isPast && ! $isTaken) {
                    $slots->push([
                        'starts_at' => $cursor->format('Y-m-d H:i:s'),
                        'ends_at' => $endsAt->format('Y-m-d H:i:s'),
                        'label' => $cursor->format('H:i'),
                    ]);
                }

                $cursor = $cursor->addMinutes(self::SLOT_MINUTES);
            }
        }

        return $slots;
    }
}
