<?php

use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[\Livewire\Features\SupportPageComponents\BaseLayout('layouts::app.portal')] #[Title('Reçetelerim')] class extends Component {
    /**
     * The patient record of the signed-in user.
     */
    #[Computed]
    public function patient(): ?Patient
    {
        return Auth::user()->patient;
    }

    /**
     * The patient's prescriptions with items.
     */
    #[Computed]
    public function prescriptions(): mixed
    {
        return $this->patient
            ?->prescriptions()
            ->with(['items.drug', 'prescriber'])
            ->orderByDesc('issued_at')
            ->get();
    }

}; ?>

<section class="w-full">


    <div class="space-y-10">
        <header>
            <h1 class="text-3xl font-semibold tracking-tight text-bk-navy">{{ __('portal.prescriptions.title') }}</h1>
            <p class="mt-2 text-zinc-600">{{ __('portal.prescriptions.subtitle') }}</p>
        </header>

        <div class="space-y-6">
            @forelse ($this->prescriptions as $prescription)
                <section class="overflow-hidden rounded-2xl border border-zinc-200/70 bg-white shadow-bk">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-zinc-100 bg-zinc-50/70 px-6 py-4">
                        <div>
                            <p class="text-sm font-semibold text-bk-navy tabular-nums">
                                {{ __('portal.prescriptions.number', ['number' => $prescription->id]) }}
                                · {{ $prescription->issued_at->translatedFormat('d F Y') }}
                            </p>
                            <p class="mt-0.5 text-sm text-zinc-500">
                                @if ($prescription->prescriber)
                                    {{ __('portal.prescriptions.prescriber') }}: {{ $prescription->prescriber->name }}
                                @endif
                            </p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $prescription->status->value === 'active' ? 'bg-bk-blue-soft text-bk-blue-dark' : ($prescription->status->value === 'dispensed' ? 'bg-emerald-50 text-emerald-700' : 'bg-zinc-100 text-zinc-500') }}">
                            {{ $prescription->status->getLabel() }}
                        </span>
                    </div>

                    <div class="divide-y divide-zinc-100">
                        @foreach ($prescription->items as $item)
                            <div class="grid gap-3 px-6 py-5 sm:grid-cols-[2fr_1fr_2fr]">
                                <div>
                                    <p class="font-medium text-bk-navy">{{ $item->drug->name }}</p>
                                    <p class="text-sm text-zinc-500">
                                        {{ $item->drug->strength }} {{ $item->drug->form ? '· '.$item->drug->form : '' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-zinc-400">{{ __('portal.prescriptions.dosage') }}</p>
                                    <p class="mt-1 text-sm text-zinc-700">{{ $item->dosage ?? '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-zinc-400">{{ __('portal.prescriptions.instructions') }}</p>
                                    <p class="mt-1 text-sm text-zinc-600">{{ $item->frequency ?? '—' }}@if ($item->duration_days) · {{ $item->duration_days }} {{ __('portal.prescriptions.days') }} @endif</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="rounded-2xl border border-dashed border-zinc-300 p-10 text-center">
                    <flux:icon name="clipboard-document-check" class="mx-auto size-8 text-zinc-300" />
                    <p class="mt-3 text-sm text-zinc-500">{{ __('portal.prescriptions.empty') }}</p>
                </div>
            @endforelse
        </div>
    </div>

</section>