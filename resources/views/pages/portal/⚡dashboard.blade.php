<?php

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[\Livewire\Features\SupportPageComponents\BaseLayout('layouts::app.portal')] #[Title('Hasta Portalı')] class extends Component {
    /**
     * The signed-in patient user.
     */
    #[Computed]
    public function user(): User
    {
        return Auth::user();
    }

    /**
     * The patient record of the signed-in user.
     */
    #[Computed]
    public function patient(): ?Patient
    {
        return $this->user->patient;
    }

    /**
     * The next upcoming appointment.
     */
    #[Computed]
    public function nextAppointment(): ?Appointment
    {
        return $this->patient
            ?->appointments()
            ->whereIn('status', ['scheduled', 'checked_in'])
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->first();
    }

    /**
     * The patient's outstanding balance.
     */
    #[Computed]
    public function balance(): float
    {
        return $this->patient?->balance ?? 0.0;
    }

}; ?>

<section class="w-full">


    @if ($this->patient)
        <div class="space-y-10">
            <header class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight text-bk-navy sm:text-4xl">
                        {{ __('portal.dashboard.greeting', ['name' => $this->patient->first_name]) }}
                    </h1>
                    <p class="mt-2 text-zinc-600">
                        {{ __('portal.dashboard.subtitle') }}
                        <span class="font-medium text-bk-blue">{{ now()->translatedFormat('d F Y') }}</span>
                    </p>
                </div>
                <span class="rounded-full border border-bk-border bg-bk-blue-soft px-3 py-1 text-xs font-semibold tracking-wide text-bk-blue-dark">
                    {{ __('portal.dashboard.mrn_label') }} {{ $this->patient->mrn }}
                </span>
            </header>

            @if ($this->nextAppointment)
                <section class="overflow-hidden rounded-2xl border border-bk-border bg-white shadow-bk">
                    <div class="flex flex-col gap-6 bg-gradient-to-r from-bk-blue-soft via-white to-white px-6 py-6 sm:flex-row sm:items-center sm:px-10 sm:py-8">
                        <div class="grid size-20 shrink-0 place-items-center rounded-2xl bg-bk-blue text-white shadow-bk sm:size-24">
                            <span class="text-center leading-none">
                                <span class="block text-2xl font-bold sm:text-3xl">{{ $this->nextAppointment->starts_at->format('d') }}</span>
                                <span class="mt-1 block text-[11px] font-semibold uppercase tracking-widest text-white/80">{{ $this->nextAppointment->starts_at->translatedFormat('M') }}</span>
                            </span>
                        </div>
                        <div class="flex-1 space-y-1">
                            <h2 class="text-lg font-semibold tracking-tight text-bk-navy">{{ __('portal.dashboard.next_appointment') }}</h2>
                            <p class="text-sm text-zinc-600">
                                {{ $this->nextAppointment->starts_at->translatedFormat('l, d F Y — H:i') }}
                            </p>
                            <p class="text-sm text-zinc-500">
                                @if ($this->nextAppointment->provider)
                                    {{ __('portal.appointments.with_provider', ['provider' => $this->nextAppointment->provider->name]) }}
                                @endif
                                @if ($this->nextAppointment->facility)
                                    · {{ $this->nextAppointment->facility->name }}
                                @endif
                                @if ($this->nextAppointment->room)
                                    · {{ __('portal.appointments.room', ['room' => $this->nextAppointment->room]) }}
                                @endif
                            </p>
                        </div>
                        <div class="shrink-0">
                            <flux:button variant="primary" :href="route('portal.appointments')" wire:navigate>
                                {{ __('portal.dashboard.view_all') }}
                                <flux:icon name="arrow-right" class="size-4" />
                            </flux:button>
                        </div>
                    </div>
                </section>
            @else
                <flux:callout variant="brand" icon="calendar-days">
                    <flux:callout.heading>{{ __('portal.dashboard.no_upcoming') }}</flux:callout.heading>
                    <flux:callout.text>{{ __('portal.dashboard.call_clinic') }}</flux:callout.text>
                </flux:callout>
            @endif

            <div class="grid gap-10 lg:grid-cols-5">
                <section class="lg:col-span-3">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-xl font-semibold tracking-tight text-bk-navy">{{ __('portal.dashboard.recent_activity') }}</h2>
                        <flux:link :href="route('portal.appointments')" wire:navigate class="text-sm font-medium text-bk-blue hover:text-bk-blue-dark">
                            {{ __('portal.common.view_all') }}
                        </flux:link>
                    </div>

                    <flux:card class="divide-y divide-zinc-100 p-0">
                        @forelse ($this->patient->encounters()->with('provider')->latest('started_at')->limit(4)->get() as $encounter)
                            <div class="flex items-start justify-between gap-4 p-5">
                                <div>
                                    <p class="font-medium text-bk-navy">{{ $encounter->chief_complaint ?? __('portal.invoices.visit') }}</p>
                                    <p class="mt-1 text-sm text-zinc-500">
                                        {{ $encounter->started_at->translatedFormat('d F Y') }}
                                        @if ($encounter->provider)
                                            · {{ $encounter->provider->name }}
                                        @endif
                                    </p>
                                </div>
                                <span class="mt-1 shrink-0 text-xs font-medium text-zinc-400 tabular-nums">
                                    {{ $encounter->started_at->diffForHumans() }}
                                </span>
                            </div>
                        @empty
                            <div class="p-8 text-center text-sm text-zinc-500">
                                {{ __('portal.dashboard.no_activity') }}
                            </div>
                        @endforelse
                    </flux:card>
                </section>

                <aside class="space-y-4 lg:col-span-2">
                    <div class="rounded-2xl border border-bk-border bg-bk-blue-soft p-5">
                        <p class="text-sm font-medium text-bk-blue-dark">{{ __('portal.dashboard.balance_label') }}</p>
                        <p class="mt-2 text-3xl font-semibold tracking-tight text-bk-navy tabular-nums">
                            {{ number_format($this->balance, 2, ',', '.') }} ₺
                        </p>
                        <p class="mt-1 text-xs text-bk-blue/80">
                            {{ __('portal.dashboard.balance_help') }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between gap-4 rounded-xl border border-bk-border bg-white p-5 shadow-bk">
                        <div class="flex items-center gap-3">
                            <span class="grid size-10 place-items-center rounded-xl bg-bk-blue-soft text-bk-blue">
                                <flux:icon name="clipboard-document-check" class="size-5" />
                            </span>
                            <div>
                                <p class="text-sm font-medium text-bk-navy">{{ __('portal.nav.prescriptions') }}</p>
                                <p class="text-sm text-zinc-500 tabular-nums">{{ $this->patient->prescriptions()->count() }} {{ __('portal.common.records') }}</p>
                            </div>
                        </div>
                        <flux:link :href="route('portal.prescriptions')" wire:navigate class="text-sm font-medium text-bk-blue hover:text-bk-blue-dark">
                            {{ __('portal.common.details') }}
                        </flux:link>
                    </div>

                    <div class="flex items-center justify-between gap-4 rounded-xl border border-bk-border bg-white p-5 shadow-bk">
                        <div class="flex items-center gap-3">
                            <span class="grid size-10 place-items-center rounded-xl bg-bk-blue-soft text-bk-blue">
                                <flux:icon name="beaker" class="size-5" />
                            </span>
                            <div>
                                <p class="text-sm font-medium text-bk-navy">{{ __('portal.nav.lab_results') }}</p>
                                <p class="text-sm text-zinc-500 tabular-nums">
                                    {{ $this->patient->labOrders()->count() }} {{ __('portal.common.orders') }}
                                </p>
                            </div>
                        </div>
                        <flux:link :href="route('portal.lab-results')" wire:navigate class="text-sm font-medium text-bk-blue hover:text-bk-blue-dark">
                            {{ __('portal.common.details') }}
                        </flux:link>
                    </div>
                </aside>
            </div>
        </div>
    @else
        <flux:callout variant="warning" icon="exclamation-triangle">
            <flux:callout.heading>{{ __('portal.dashboard.no_patient_record') }}</flux:callout.heading>
            <flux:callout.text>{{ __('portal.dashboard.contact_support') }}</flux:callout.text>
        </flux:callout>
    @endif

</section>