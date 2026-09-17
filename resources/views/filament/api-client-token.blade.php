<x-filament-panels::page>
    <div class="max-w-xl space-y-4">
        <x-filament::section>
            <x-slot:heading>
                {{ __('app.api-clients.token_title') }}
            </x-slot:heading>

            <x-slot:description>
                {{ __('app.api-clients.token_warning') }}
            </x-slot:description>

            <x-filament::section::content>
                <div class="py-2">
                    <code class="block break-all rounded-lg bg-gray-100 dark:bg-gray-900 p-4 text-xs font-mono">
                        {{ $token }}
                    </code>
                </div>
            </x-filament::section::content>
        </x-filament::section>

        <a href="/admin/api-clients" class="text-primary-600 hover:underline text-sm">
            {{ __('app.api-clients.back') }}
        </a>
    </div>
</x-filament-panels::page>
