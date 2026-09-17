<?php

use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[\Livewire\Features\SupportPageComponents\BaseLayout('layouts::app.portal')] #[Title('Lab Sonuçlarım')] class extends Component {
    /**
     * The patient record of the signed-in user.
     */
    #[Computed]
    public function patient(): ?Patient
    {
        return Auth::user()->patient;
    }

    /**
     * The patient's lab orders with results.
     */
    #[Computed]
    public function orders(): mixed
    {
        return $this->patient
            ?->labOrders()
            ->with(['results.labTest', 'orderer'])
            ->orderByDesc('ordered_at')
            ->get();
    }

}; ?>

<section class="w-full">


    <div class="space-y-10">
        <header>
            <h1 class="text-3xl font-semibold tracking-tight text-bk-navy">{{ __('portal.lab-results.title') }}</h1>
            <p class="mt-2 text-zinc-600">{{ __('portal.lab-results.subtitle') }}</p>
        </header>

        <div class="space-y-6">
            @forelse ($this->orders as $order)
                <section class="overflow-hidden rounded-2xl border border-zinc-200/70 bg-white shadow-bk">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-zinc-100 bg-zinc-50/70 px-6 py-4">
                        <div>
                            <p class="text-sm font-semibold text-bk-navy tabular-nums">
                                {{ __('portal.lab-results.number', ['number' => $order->id]) }}
                                · {{ $order->ordered_at->translatedFormat('d F Y') }}
                            </p>
                            <p class="mt-0.5 text-sm text-zinc-500">
                                @if ($order->orderer)
                                    {{ __('portal.lab-orders.ordered_by') }}: {{ $order->orderer->name }}
                                @endif
                            </p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ in_array($order->status->value, ['resulted', 'reviewed']) ? 'bg-emerald-50 text-emerald-700' : 'bg-bk-blue-soft text-bk-blue-dark' }}">
                            {{ $order->status->getLabel() }}
                        </span>
                    </div>

                    <div class="divide-y divide-zinc-100">
                        @foreach ($order->results as $result)
                            <div class="grid gap-3 px-6 py-5 sm:grid-cols-[2fr_1fr_1fr]">
                                <div>
                                    <p class="font-medium text-bk-navy">{{ $result->labTest->name }}</p>
                                    <p class="text-sm text-zinc-500">
                                        {{ $result->labTest->code }} @if ($result->labTest->category) · {{ $result->labTest->category }} @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-zinc-400">{{ __('portal.lab-results.value') }}</p>
                                    <p class="mt-1 text-sm font-semibold tabular-nums {{ $result->is_abnormal ? 'text-amber-600' : 'text-zinc-700' }}">
                                        {{ $result->value_numeric !== null ? $result->value_numeric.' '.($result->labTest->unit ?? '') : ($result->value_text ?? '—') }}
                                        @if ($result->flag && $result->is_abnormal)
                                            <span class="ml-1 rounded bg-amber-100 px-1.5 py-0.5 text-[10px] font-bold uppercase text-amber-700">{{ $result->flag->getLabel() }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-zinc-400">{{ __('app.lab-tests.reference_range') }}</p>
                                    <p class="mt-1 text-sm text-zinc-600 tabular-nums">
                                        @if ($result->labTest->reference_min !== null)
                                            {{ $result->labTest->reference_min }} – {{ $result->labTest->reference_max }} {{ $result->labTest->unit }}
                                        @else
                                            —
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="rounded-2xl border border-dashed border-zinc-300 p-10 text-center">
                    <flux:icon name="beaker" class="mx-auto size-8 text-zinc-300" />
                    <p class="mt-3 text-sm text-zinc-500">{{ __('portal.lab-results.empty') }}</p>
                </div>
            @endforelse
        </div>
    </div>

</section>