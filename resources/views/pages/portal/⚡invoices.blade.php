<?php

use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[\Livewire\Features\SupportPageComponents\BaseLayout('layouts::app.portal')] #[Title('Faturalarım')] class extends Component {
    /**
     * The patient record of the signed-in user.
     */
    #[Computed]
    public function patient(): ?Patient
    {
        return Auth::user()->patient;
    }

    /**
     * The patient's billing lines.
     */
    #[Computed]
    public function billingLines(): mixed
    {
        return $this->patient
            ?->billingLines()
            ->with(['service', 'encounter'])
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * The patient's payments.
     */
    #[Computed]
    public function payments(): mixed
    {
        return $this->patient
            ?->payments()
            ->orderByDesc('paid_at')
            ->get();
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


    <div class="space-y-10">
        <header class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-semibold tracking-tight text-bk-navy">{{ __('portal.invoices.title') }}</h1>
                <p class="mt-2 text-zinc-600">{{ __('portal.invoices.subtitle') }}</p>
            </div>
            <div class="rounded-2xl px-6 py-4 {{ $this->balance > 0 ? 'bg-amber-50 border border-amber-200' : 'bg-emerald-50 border border-emerald-200' }}">
                <p class="text-xs font-semibold uppercase tracking-wide {{ $this->balance > 0 ? 'text-amber-700' : 'text-emerald-700' }}">
                    {{ __('portal.invoices.balance_label') }}
                </p>
                <p class="mt-1 text-2xl font-semibold tracking-tight tabular-nums {{ $this->balance > 0 ? 'text-amber-800' : 'text-emerald-800' }}">
                    {{ number_format($this->balance, 2, ',', '.') }} ₺
                </p>
            </div>
        </header>

        <section class="space-y-4">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-zinc-400">{{ __('portal.invoices.charges') }}</h2>

            <flux:card class="divide-y divide-zinc-100 p-0">
                @forelse ($this->billingLines as $line)
                    <div class="flex items-center justify-between gap-4 p-5">
                        <div>
                            <p class="font-medium text-bk-navy">{{ $line->service->name }}</p>
                            <p class="mt-0.5 text-sm text-zinc-500 tabular-nums">
                                {{ $line->created_at->translatedFormat('d F Y') }}
                                · {{ $line->quantity }} × {{ number_format($line->unit_price, 2, ',', '.') }} ₺
                                @if ($line->discount_amount > 0)
                                    · {{ __('portal.invoices.discount', ['amount' => number_format($line->discount_amount, 2, ',', '.')]) }}
                                @endif
                            </p>
                        </div>
                        <span class="shrink-0 font-semibold text-bk-navy tabular-nums">
                            {{ number_format($line->total, 2, ',', '.') }} ₺
                        </span>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-zinc-500">{{ __('portal.invoices.no_charges') }}</div>
                @endforelse
            </flux:card>
        </section>

        <section class="space-y-4">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-zinc-400">{{ __('portal.invoices.payments') }}</h2>

            <flux:card class="divide-y divide-zinc-100 p-0">
                @forelse ($this->payments as $payment)
                    <div class="flex items-center justify-between gap-4 p-5">
                        <div>
                            <p class="font-medium text-bk-navy">{{ $payment->method->getLabel() }}</p>
                            <p class="mt-0.5 text-sm text-zinc-500 tabular-nums">{{ $payment->paid_at->translatedFormat('d F Y') }}</p>
                        </div>
                        <span class="shrink-0 font-semibold text-emerald-600 tabular-nums">
                            −{{ number_format($payment->amount, 2, ',', '.') }} ₺
                        </span>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-zinc-500">{{ __('portal.invoices.no_payments') }}</div>
                @endforelse
            </flux:card>
        </section>
    </div>

</section>