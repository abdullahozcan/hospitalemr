<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-bk-surface text-bk-navy antialiased">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-bk-border bg-white">
            <flux:sidebar.header>
                <a href="{{ route('portal.dashboard') }}" wire:navigate class="flex items-center gap-2.5 px-2 py-1">
                    <span class="grid size-9 place-items-center rounded-[10px] bg-bk-blue shadow-sm shadow-bk-blue/30">
                        <flux:icon class="size-5 text-white" name="heart" variant="solid" />
                    </span>
                    <span class="grid leading-tight">
                        <span class="text-sm font-semibold tracking-tight text-bk-navy">{{ config('app.name') }}</span>
                        <span class="text-[11px] font-medium text-bk-blue">{{ __('portal.brand.tagline') }}</span>
                    </span>
                </a>
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.item icon="home" :href="route('portal.dashboard')" :current="request()->routeIs('portal.dashboard')" wire:navigate>
                    {{ __('portal.nav.dashboard') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="calendar" :href="route('portal.appointments')" :current="request()->routeIs('portal.appointments')" wire:navigate>
                    {{ __('portal.nav.appointments') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="clipboard-document-check" :href="route('portal.prescriptions')" :current="request()->routeIs('portal.prescriptions')" wire:navigate>
                    {{ __('portal.nav.prescriptions') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="beaker" :href="route('portal.lab-results')" :current="request()->routeIs('portal.lab-results')" wire:navigate>
                    {{ __('portal.nav.lab_results') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="banknotes" :href="route('portal.invoices')" :current="request()->routeIs('portal.invoices')" wire:navigate>
                    {{ __('portal.nav.invoices') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="user" :href="route('portal.profile')" :current="request()->routeIs('portal.profile')" wire:navigate>
                    {{ __('portal.nav.profile') }}
                </flux:sidebar.item>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:sidebar.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full cursor-pointer">
                        {{ __('portal.nav.logout') }}
                    </flux:sidebar.item>
                </form>
            </flux:sidebar.nav>
        </flux:sidebar>

        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            <span class="text-sm font-semibold tracking-tight text-bk-navy">{{ config('app.name') }}</span>
            <flux:spacer />
        </flux:header>

        <flux:main class="mx-auto w-full max-w-5xl px-6 py-10 sm:px-10">
            {{ $slot }}
        </flux:main>
    </body>
</html>
