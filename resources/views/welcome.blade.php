<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white text-bk-navy antialiased">
        <header class="sticky top-0 z-40 border-b border-bk-border bg-white/90 backdrop-blur">
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <span class="grid size-9 place-items-center rounded-[10px] bg-bk-blue shadow-bk">
                        <x-app-logo-icon class="size-5 fill-current text-white" />
                    </span>
                    <span class="grid leading-tight">
                        <span class="text-sm font-semibold tracking-tight">{{ config('app.name') }}</span>
                        <span class="text-[11px] font-medium text-bk-blue">{{ __('public.home.tagline') }}</span>
                    </span>
                </a>

                <nav class="hidden items-center gap-6 text-sm font-medium text-bk-slate md:flex">
                    <a href="{{ route('doctors.index') }}" class="hover:text-bk-navy">{{ __('public.nav.doctors') }}</a>
                    <a href="{{ route('home') }}#nasil" class="hover:text-bk-navy">{{ __('public.nav.how_it_works') }}</a>
                </nav>

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
                        <a href="{{ route('register') }}"
                           class="hidden rounded-lg px-4 py-2 text-sm font-semibold text-bk-navy transition hover:bg-bk-surface sm:block">
                            {{ __('public.nav.register') }}
                        </a>
                        <a href="{{ route('login') }}"
                           class="rounded-lg bg-bk-blue px-4 py-2 text-sm font-semibold text-white shadow-bk transition hover:bg-bk-blue-dark">
                            {{ __('public.nav.login') }}
                        </a>
                    @endguest
                </div>
            </div>
        </header>

        <main>
            {{-- Hero + arama --}}
            <section class="relative overflow-hidden bg-gradient-to-b from-bk-blue-soft via-white to-white">
                <div class="mx-auto max-w-6xl px-6 pb-16 pt-14 text-center sm:pt-20">
                    <h1 class="mx-auto max-w-3xl text-4xl font-semibold leading-tight tracking-tight text-bk-navy sm:text-5xl">
                        {{ __('public.home.hero_line_1') }}
                        <span class="block text-bk-blue">{{ __('public.home.hero_line_2') }}</span>
                    </h1>
                    <p class="mx-auto mt-4 max-w-xl text-bk-slate">
                        {{ __('public.home.hero_sub') }}
                    </p>

                    <form action="{{ route('doctors.index') }}" method="GET"
                          class="mx-auto mt-8 flex max-w-xl items-stretch gap-2 rounded-xl border border-bk-border bg-white p-2 shadow-bk">
                        <span class="grid w-10 place-items-center text-bk-slate">
                            <flux:icon name="magnifying-glass" class="size-5" />
                        </span>
                        <input type="search" name="q" value=""
                               placeholder="{{ __('public.home.search_placeholder') }}"
                               class="w-full bg-transparent py-2 text-sm outline-none placeholder:text-bk-slate/60">
                        <button type="submit"
                                class="rounded-lg bg-bk-blue px-5 py-2 text-sm font-semibold text-white transition hover:bg-bk-blue-dark">
                            {{ __('public.home.search') }}
                        </button>
                    </form>

                    <div class="mt-4 flex flex-wrap items-center justify-center gap-2 text-xs text-bk-slate">
                        <span>{{ __('public.home.popular') }}</span>
                        @foreach (['Kontrol', 'Online Görüşme', 'Lab Sonucu', 'Reçete'] as $topic)
                            <a href="{{ route('doctors.index') }}"
                               class="rounded-full border border-bk-border bg-bk-surface px-3 py-1 font-medium text-bk-navy transition hover:bg-bk-blue-soft">
                                {{ $topic }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- Doktor kartları --}}
            <section class="mx-auto max-w-6xl px-6 py-14">
                <div class="mb-8 flex items-end justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-semibold tracking-tight text-bk-navy">{{ __('public.home.doctors_title') }}</h2>
                        <p class="mt-1 text-sm text-bk-slate">{{ __('public.home.doctors_sub') }}</p>
                    </div>
                    <a href="{{ route('doctors.index') }}" class="text-sm font-semibold text-bk-blue hover:text-bk-blue-dark">
                        {{ __('public.home.view_all_doctors') }} →
                    </a>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($doctors->take(6) as $doctor)
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

                            <div class="flex items-center justify-between gap-2 text-xs text-bk-slate">
                                <span class="inline-flex items-center gap-1.5">
                                    <flux:icon name="building-office-2" class="size-4" />
                                    {{ $doctor->providerSchedules->pluck('facility.name')->filter()->unique()->implode(', ') ?: __('public.home.no_facility') }}
                                </span>
                            </div>

                            <a href="{{ route('doctors.book.show', $doctor) }}"
                               class="block w-full rounded-lg bg-bk-blue py-2 text-center text-sm font-semibold text-white transition hover:bg-bk-blue-dark">
                                {{ __('public.home.book_now') }}
                            </a>
                        </div>
                    @empty
                        <p class="col-span-full rounded-xl border border-dashed border-bk-border p-10 text-center text-sm text-bk-slate">
                            {{ __('public.home.no_doctors') }}
                        </p>
                    @endforelse
                </div>
            </section>

            {{-- Nasıl çalışır --}}
            <section id="nasil-calisir" class="border-t border-bk-border bg-bk-surface">
                <div class="mx-auto max-w-6xl px-6 py-14">
                    <h2 class="text-center text-2xl font-semibold tracking-tight text-bk-navy">{{ __('public.home.how_title') }}</h2>

                    <div class="mt-10 grid gap-8 sm:grid-cols-3">
                        @foreach ([
                            'magnifying-glass' => ['how_1_title', 'how_1_text'],
                            'calendar' => ['how_2_title', 'how_2_text'],
                            'video-camera' => ['how_3_title', 'how_3_text'],
                        ] as $icon => [$titleKey, $textKey])
                            <div class="flex flex-col items-center gap-3 text-center">
                                <span class="grid size-12 place-items-center rounded-xl bg-bk-blue-soft text-bk-blue">
                                    <flux:icon name="{{ $icon }}" class="size-6" />
                                </span>
                                <h3 class="font-semibold text-bk-navy">{{ __("public.home.$titleKey") }}</h3>
                                <p class="text-sm leading-relaxed text-bk-slate">{{ __("public.home.$textKey") }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-12 text-center">
                        <a href="{{ route('doctors.index') }}"
                           class="inline-block rounded-lg bg-bk-blue px-6 py-3 text-sm font-semibold text-white shadow-bk transition hover:bg-bk-blue-dark">
                            {{ __('public.home.cta_find_doctor') }}
                        </a>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-bk-border bg-white">
            <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-6 py-8 text-sm text-bk-slate sm:flex-row">
                <div class="flex items-center gap-2">
                    <span class="grid size-7 place-items-center rounded-lg bg-bk-blue">
                        <x-app-logo-icon class="size-4 fill-current text-white" />
                    </span>
                    <span class="font-semibold text-bk-navy">{{ config('app.name') }}</span>
                </div>
                <p>© {{ now()->year }} {{ config('app.name') }} — {{ __('public.footer.rights') }}</p>
            </div>
        </footer>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist
        @fluxScripts
    </body>
</html>
