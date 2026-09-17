<?php

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[\Livewire\Features\SupportPageComponents\BaseLayout('layouts::app.portal')] #[Title('Profilim')] class extends Component {
    public string $name = '';

    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfile(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email') && $user instanceof MustVerifyEmail) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated');
    }

}; ?>

<section class="w-full">


    <div class="space-y-10">
        <header>
            <h1 class="text-3xl font-semibold tracking-tight text-bk-navy">{{ __('portal.profile.title') }}</h1>
            <p class="mt-2 text-zinc-600">{{ __('portal.profile.subtitle') }}</p>
        </header>

        <div class="space-y-6">
            <section class="rounded-2xl border border-zinc-200/70 bg-white p-6 shadow-bk">
                <h2 class="text-lg font-semibold tracking-tight text-bk-navy">{{ __('portal.profile.personal_info') }}</h2>
                <form wire:submit="updateProfile" class="mt-6 space-y-5">
                    <div class="grid gap-5 sm:grid-cols-2">
                        <flux:field>
                            <flux:label>{{ __('portal.profile.name') }}</flux:label>
                            <flux:input wire:model="name" />
                            <flux:error name="name" />
                        </flux:field>

                        <flux:field>
                            <flux:label>{{ __('portal.profile.email') }}</flux:label>
                            <flux:input wire:model="email" type="email" />
                            <flux:error name="email" />
                        </flux:field>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center justify-end">
                            <flux:button variant="primary" type="submit" data-test="update-profile-button">
                                {{ __('Save') }}
                            </flux:button>
                        </div>

                        <x-action-message on="profile-updated">
                            {{ __('Saved.') }}
                        </x-action-message>
                    </div>
                </form>
            </section>

            <section class="rounded-2xl border border-zinc-200/70 bg-white p-6 shadow-bk">
                <h2 class="text-lg font-semibold tracking-tight text-bk-navy">{{ __('portal.profile.account') }}</h2>
                <dl class="mt-4 grid gap-4 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-zinc-500">{{ __('portal.profile.mrn') }}</dt>
                        <dd class="mt-1 font-medium text-bk-navy tabular-nums">{{ Auth::user()->patient?->mrn ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-zinc-500">{{ __('portal.profile.birth_date') }}</dt>
                        <dt class="mt-1 font-medium text-bk-navy tabular-nums">
                            {{ Auth::user()->patient?->birth_date?->format('d.m.Y') ?? '—' }}
                        </dt>
                    </div>
                    <div>
                        <dt class="text-zinc-500">{{ __('app.patient-form.blood_type') }}</dt>
                        <dt class="mt-1 font-medium text-bk-navy">{{ Auth::user()->patient?->blood_type ?? '—' }}</dt>
                    </div>
                    <div>
                        <dt class="text-zinc-500">{{ __('app.patient-form.national_id') }}</dt>
                        <dt class="mt-1 font-medium text-bk-navy tabular-nums">{{ Auth::user()->patient?->national_id ?? '—' }}</dt>
                    </div>
                </dl>
            </section>
        </div>
    </div>

</section>