<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-bk-surface text-bk-navy antialiased">
        <header class="sticky top-0 z-40 border-b border-bk-border bg-white/90 backdrop-blur">
            <div class="mx-auto flex h-16 max-w-4xl items-center justify-between px-6">
                <a href="{{ route('doctors.index') }}" class="flex items-center gap-2 text-sm font-medium text-bk-slate hover:text-bk-navy">
                    <flux:icon name="arrow-left" class="size-4" />
                    {{ __('public.booking.back_to_doctors') }}
                </a>
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="grid size-8 place-items-center rounded-lg bg-bk-blue">
                        <x-app-logo-icon class="size-4 fill-current text-white" />
                    </span>
                    <span class="text-sm font-semibold tracking-tight">{{ config('app.name') }}</span>
                </a>
            </div>
        </header>

        <main class="mx-auto max-w-4xl px-6 py-10">
            @if (session('error'))
                <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm font-medium text-amber-800">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Doktor kartı --}}
            <section class="mb-8 flex items-center gap-4 rounded-xl border border-bk-border bg-white p-5 shadow-bk">
                <span class="grid size-14 shrink-0 place-items-center rounded-full bg-bk-blue-soft text-base font-bold text-bk-blue">
                    {{ \Illuminate\Support\Str::initials($doctor->name) }}
                </span>
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-bk-navy">Dr. {{ $doctor->name }}</h1>
                    <p class="text-sm text-bk-slate">
                        {{ __('public.home.doctor_role') }}
                        @if ($doctor->providerSchedules->pluck('facility.name')->filter()->unique()->isNotEmpty())
                            · {{ $doctor->providerSchedules->pluck('facility.name')->filter()->unique()->implode(', ') }}
                        @endif
                    </p>
                </div>
            </section>

            {{-- Gün sekmeleri --}}
            <section class="mb-6">
                <h2 class="mb-3 text-sm font-semibold uppercase tracking-widest text-bk-slate">{{ __('public.booking.pick_date') }}</h2>
                <div class="grid grid-cols-4 gap-2 sm:grid-cols-7">
                    @foreach ($days as $day)
                        <a href="{{ route('doctors.book.show', ['doctor' => $doctor, 'date' => $day->format('Y-m-d')]) }}"
                           class="rounded-lg border px-2 py-2.5 text-center transition {{ $day->isSameDay($activeDate)
                                ? 'border-bk-blue bg-bk-blue text-white shadow-bk'
                                : 'border-bk-border bg-white hover:bg-bk-blue-soft' }}">
                            <span class="block text-[10px] font-semibold uppercase tracking-wide {{ $day->isSameDay($activeDate) ? 'text-white/80' : 'text-bk-slate' }}">
                                {{ $day->translatedFormat('D') }}
                            </span>
                            <span class="block text-lg font-bold tabular-nums {{ $day->isSameDay($activeDate) ? 'text-white' : 'text-bk-navy' }}">
                                {{ $day->format('d') }}
                            </span>
                            <span class="block text-[10px] {{ $day->isSameDay($activeDate) ? 'text-white/70' : 'text-bk-slate' }}">
                                {{ $day->translatedFormat('M') }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </section>

            {{-- Saat slotları --}}
            <section>
                <h2 class="mb-3 text-sm font-semibold uppercase tracking-widest text-bk-slate">
                    {{ __('public.booking.pick_slot', ['date' => $activeDate->translatedFormat('d F Y, l')]) }}
                </h2>

                <div class="rounded-xl border border-bk-border bg-white p-5 shadow-bk">
                    @forelse ($slots as $slot)
                        <form action="{{ route('doctors.book.store', $doctor) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="starts_at" value="{{ $slot['starts_at'] }}">
                            <input type="hidden" name="ends_at" value="{{ $slot['ends_at'] }}">
                            @auth
                                <button type="submit"
                                        class="m-1 rounded-lg border border-bk-blue/20 bg-bk-blue-soft px-4 py-2.5 text-sm font-semibold text-bk-blue transition hover:bg-bk-blue hover:text-white">
                                    {{ $slot['label'] }}
                                </button>
                            @else
                                <a href="{{ route('login') }}"
                                   class="m-1 inline-block rounded-lg border border-bk-blue/20 bg-bk-blue-soft px-4 py-2.5 text-sm font-semibold text-bk-blue transition hover:bg-bk-blue hover:text-white">
                                    {{ $slot['label'] }}
                                </a>
                            @endauth
                        </form>
                    @empty
                        <p class="py-6 text-center text-sm text-bk-slate">{{ __('public.booking.no_slots') }}</p>
                    @endforelse

                    @guest
                        <div class="mt-4 flex flex-col items-center gap-3 border-t border-bk-border pt-5 text-center">
                            <p class="text-sm text-bk-slate">{{ __('public.booking.login_prompt') }}</p>
                            <a href="{{ route('login') }}"
                               class="rounded-lg bg-bk-blue px-5 py-2.5 text-sm font-semibold text-white shadow-bk transition hover:bg-bk-blue-dark">
                                {{ __('public.booking.login_to_book') }}
                            </a>
                        </div>
                    @endguest
                </div>

                <p class="mt-4 flex items-center gap-1.5 text-xs text-bk-slate">
                    <flux:icon name="clock" class="size-4" />
                    {{ __('public.booking.slot_note') }}
                </p>
            </section>
        </main>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist
        @fluxScripts
    </body>
</html>
