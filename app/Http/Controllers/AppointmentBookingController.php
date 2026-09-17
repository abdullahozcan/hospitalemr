<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Rules\NoOverlappingAppointment;
use App\Services\SlotAvailabilityService;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AppointmentBookingController extends Controller
{
    /**
     * Create the controller.
     */
    public function __construct(protected SlotAvailabilityService $slots) {}

    /**
     * Show the doctor's bookable slots for a date.
     */
    public function show(Request $request, User $doctor): View
    {
        abort_unless($doctor->hasRole('doctor'), 404);

        $date = Carbon::parse(is_string($request->query('date')) ? $request->query('date') : now());

        $days = collect(range(0, 6))
            ->map(fn (int $offset) => now()->startOfDay()->addDays($offset));

        $activeDate = $days->first(
            fn (Carbon|CarbonImmutable $day) => $day->isSameDay($date),
            $days->first(),
        );

        return view('public.booking', [
            'doctor' => $doctor,
            'days' => $days,
            'activeDate' => $activeDate,
            'slots' => $this->slots->forDoctor($doctor, $activeDate),
        ]);
    }

    /**
     * Book the selected slot.
     */
    public function store(Request $request, User $doctor): RedirectResponse
    {
        abort_unless($doctor->hasRole('doctor'), 404);

        $validated = $request->validate([
            'starts_at' => ['required', 'date', new NoOverlappingAppointment((int) $doctor->id, $request->input('ends_at'))],
            'ends_at' => ['required', 'date', 'after:starts_at'],
        ]);

        $user = $request->user();

        $patient = Patient::query()->find($user->patient_id);

        if ($patient === null) {
            return back()->with('error', __('public.booking.requires_patient_account'));
        }

        $appointment = Appointment::create([
            'team_id' => $doctor->providerSchedules()->first()->team_id ?? $patient->team_id,
            'patient_id' => $patient->id,
            'provider_id' => $doctor->id,
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'status' => AppointmentStatus::Scheduled->value,
        ]);

        return redirect()
            ->route('portal.appointments')
            ->with('success', __('public.booking.success'));
    }
}
