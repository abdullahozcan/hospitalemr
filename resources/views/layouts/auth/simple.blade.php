<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-bk-surface antialiased">
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <a href="{{ route('home') }}" class="mb-1 flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="grid h-11 w-11 items-center justify-center rounded-[10px] bg-bk-blue shadow-bk">
                        <x-app-logo-icon class="size-6 fill-current text-white" />
                    </span>
                    <span class="text-sm font-semibold tracking-tight text-bk-navy">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="flex flex-col gap-6">
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
