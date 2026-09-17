<?php

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[\Livewire\Features\SupportPageComponents\BaseLayout('layouts::app.portal')] #[Title('Randevularım')] class extends Component {
    /**
     * The patient record of the signed-in user.
     */
    #[Computed]
    public function patient(): ?Patient
    {
        return Auth::user()->patient;
    }

    /**
     * Upcoming appointments.
     */
    #[Computed]
    public function upcoming(): mixed
    {
        return $this->patient
            ?->appointments()
            ->with(['provider', 'facility'])
            ->whereIn('status', ['scheduled', 'checked_in', 'in_progress'])
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->get();
    }

    /**
     * Past appointments.
     */
    #[Computed]
    public function past(): mixed
    {
        return $this->patient
            ?->appointments()
            ->with(['provider', 'facility'])
            ->where(fn ($query) => $query
                ->where('starts_at', '<', now())
                ->orWhereIn('status', ['completed', 'cancelled', 'no_show']))
            ->orderByDesc('starts_at')
            ->limit(20)
            ->get();
    }

}; ?>

<section class="w-full">


    <div class="space-y-10">
        <header>
            <h1 class="text-3xl font-semibold tracking-tight text-bk-navy">{{ __('portal.appointments.title') }}</h1>
            <p class="mt-2 text-zinc-600">{{ __('portal.appointments.subtitle') }}</p>
        </header>

        <section class="space-y-4">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-bk-blue">{{ __('portal.appointments.upcoming') }}</h2>

            @forelse ($this->upcoming as $appointment)
                <div class="flex flex-col gap-4 rounded-2xl border border-bk-border bg-white p-5 shadow-bk sm:flex-row sm:items-center sm:gap-6">
                    <div class="flex w-full items-center gap-4 sm:w-52">
                        <span class="grid size-14 shrink-0 place-items-center rounded-xl bg-bk-blue text-white">
                            <span class="text-center leading-none">
                                <span class="block text-lg font-bold">{{ $appointment->starts_at->format('d') }}</span>
                                <span class="block text-[10px] font-semibold uppercase tracking-widest text-white/80">{{ $appointment->starts_at->translatedFormat('M') }}</span>
                            </span>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-bk-navy tabular-nums">{{ $appointment->starts_at->format('H:i') }}</p>
                            <p class="text-xs text-zinc-500 tabular-nums">{{ $appointment->starts_at->translatedFormat('l') }}</p>
                        </div>
                    </div>

                    <div class="flex-1">
                        <p class="font-medium text-bk-navy">
                            {{ $appointment->title ?? __('portal.appointments.default_title') }}
                        </p>
                        <p class="mt-0.5 text-sm text-zinc-500">
                            @if ($appointment->provider)
                                {{ __('portal.appointments.with_provider', ['provider' => $appointment->provider->name]) }}
                            @endif
                            @if ($appointment->facility)
                                · {{ $appointment->facility->name }}
                            @endif
                            @if ($appointment->room)
                                · {{ __('portal.appointments.room', ['room' => $appointment->room]) }}
                            @endif
                        </p>
                    </div>

                    <span class="self-start rounded-full border border-bk-border bg-bk-blue-soft px-3 py-1 text-xs font-semibold text-bk-blue-dark">
                        {{ $appointment->status->getLabel() }}
                    </span>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-zinc-300 p-8 text-center text-sm text-zinc-500">
                    {{ __('portal.appointments.no_upcoming') }}
                </div>
            @endforelse
        </section>

        <section class="space-y-4">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-zinc-400">{{ __('portal.appointments.past') }}</h2>

            <flux:card class="divide-y divide-zinc-100 p-0">
                @forelse ($this->past as $appointment)
                    <div class="flex items-center justify-between gap-4 p-5">
                        <div>
                            <p class="font-medium text-bk-navy tabular-nums">
                                {{ $appointment->starts_at->translatedFormat('d F Y') }} — {{ $appointment->starts_at->format('H:i') }}
                            </p>
                            <p class="mt-0.5 text-sm text-zinc-500">
                                {{ $appointment->title ?? __('portal.appointments.default_title') }}
                                @if ($appointment->provider)
                                    · {{ $appointment->provider->name }}
                                @endif
                            </p>
                        </div>
                        <span class="shrink-0 text-xs font-medium {{ $appointment->status === \App\Enums\AppointmentStatus::Completed ? 'text-emerald-600' : 'text-zinc-400' }}">
                            {{ $appointment->status->getLabel() }}
                        </span>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-zinc-500">
                        {{ __('portal.appointments.no_past') }}
                    </div>
                @endforelse
            </flux:card>
        </section>
    </div>

</section>