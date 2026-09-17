<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-bk-surface antialiased">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:grid-cols-2 lg:px-0">
            <div class="relative hidden h-full flex-col p-10 text-white lg:flex">
                <div class="absolute inset-0 bg-gradient-to-b from-bk-blue to-bk-blue-dark"></div>
                <a href="{{ route('home') }}" class="relative z-20 flex items-center text-lg font-medium" wire:navigate>
                    <span class="grid h-10 w-10 items-center justify-center rounded-[10px] bg-white/15">
                        <x-app-logo-icon class="me-1 h-7 fill-current text-white" />
                    </span>
                    <span class="ms-2 font-semibold tracking-tight">{{ config('app.name', 'Laravel') }}</span>
                </a>

                <div class="relative z-20 mt-auto">
                    <p class="text-2xl font-semibold leading-snug tracking-tight">
                        {{ __('portal.brand.auth_pitch') }}
                    </p>
                    <p class="mt-3 text-sm text-white/70">
                        {{ __('portal.brand.auth_pitch_sub') }}
                    </p>
                </div>
            </div>
            <div class="w-full lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden" wire:navigate>
                        <span class="grid h-10 w-10 items-center justify-center rounded-[10px] bg-bk-blue shadow-bk">
                            <x-app-logo-icon class="size-5 fill-current text-white" />
                        </span>

                        <span class="text-sm font-semibold tracking-tight text-bk-navy">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
