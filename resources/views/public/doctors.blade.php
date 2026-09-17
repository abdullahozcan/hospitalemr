<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-bk-surface text-bk-navy antialiased">
        <header class="sticky top-0 z-40 border-b border-bk-border bg-white/90 backdrop-blur">
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <span class="grid size-9 place-items-center rounded-[10px] bg-bk-blue shadow-bk">
                        <x-app-logo-icon class="size-5 fill-current text-white" />
                    </span>
                    <span class="text-sm font-semibold tracking-tight">{{ config('app.name') }}</span>
                </a>

                <div class="flex items-center gap-2">
                    @auth
                        @if (auth()->user()->isPatient())
                            <a href="{{ route('portal.dashboard') }}"
                               class="rounded-lg bg-bk-blue px-4 py-2 text-sm font-semibold text-white shadow-bk transition hover:bg-bk-blue-dark">
                                {{ __('public.nav.my_portal') }}
                            </a>
                        @else
                            <a href="/admin"
                               class="rounded-lg bg-bk-blue px-4 py-2 text-sm font-semibold text-white shadow-bk transition hover:bg-bk-blue-dark">
                                {{ __('public.nav.admin_panel') }}
                            </a>
                        @endif
                    @endauth
                    @guest
                        <a href="{{ route('login') }}"
                           class="rounded-lg bg-bk-blue px-4 py-2 text-sm font-semibold text-white shadow-bk transition hover:bg-bk-blue-dark">
                            {{ __('public.nav.login') }}
                        </a>
                    @endguest
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-6 py-10">
            <header class="mb-8">
                <h1 class="text-3xl font-semibold tracking-tight text-bk-navy">{{ __('public.doctors.title') }}</h1>
                <p class="mt-2 text-sm text-bk-slate">{{ __('public.doctors.subtitle') }}</p>

                <form action="{{ route('doctors.index') }}" method="GET"
                      class="mt-6 flex max-w-xl items-stretch gap-2 rounded-xl border border-bk-border bg-white p-2 shadow-bk">
                    <span class="grid w-10 place-items-center text-bk-slate">
                        <flux:icon name="magnifying-glass" class="size-5" />
                    </span>
                    <input type="search" name="q" value="{{ $q }}"
                           placeholder="{{ __('public.home.search_placeholder') }}"
                           class="w-full bg-transparent py-2 text-sm outline-none placeholder:text-bk-slate/60">
                    <button type="submit"
                            class="rounded-lg bg-bk-blue px-5 py-2 text-sm font-semibold text-white transition hover:bg-bk-blue-dark">
                        {{ __('public.home.search') }}
                    </button>
                </form>
            </header>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($doctors as $doctor)
                    <div class="flex flex-col justify-between gap-4 rounded-xl border border-bk-border bg-white p-5 shadow-bk transition hover:border-bk-blue/40">
                        <div class="flex items-center gap-3">
                            <span class="grid size-11 shrink-0 place-items-center rounded-full bg-bk-blue-soft text-sm font-bold text-bk-blue">
                                {{ \Illuminate\Support\Str::initials($doctor->name) }}
                            </span>
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-bk-navy">Dr. {{ $doctor->name }}</p>
                                <p class="text-xs font-medium text-bk-slate">{{ __('public.home.doctor_role') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5 text-xs text-bk-slate">
                            <flux:icon name="building-office-2" class="size-4" />
                            <span class="truncate">{{ $doctor->providerSchedules->pluck('facility.name')->filter()->unique()->implode(', ') ?: __('public.home.no_facility') }}</span>
                        </div>

                        <a href="{{ route('doctors.book.show', $doctor) }}"
                           class="block w-full rounded-lg bg-bk-blue py-2 text-center text-sm font-semibold text-white transition hover:bg-bk-blue-dark">
                            {{ __('public.home.book_now') }}
                        </a>
                    </div>
                @empty
                    <p class="col-span-full rounded-xl border border-dashed border-bk-border bg-white p-10 text-center text-sm text-bk-slate">
                        {{ __('public.doctors.no_results') }}
                    </p>
                @endforelse
            </div>
        </main>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist
        @fluxScripts
    </body>
</html>
