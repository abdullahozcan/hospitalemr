<?php

namespace App\Filament\Resources\Patients\Pages;

use App\Enums\TeamRole;
use App\Filament\Resources\Patients\PatientResource;
use App\Models\Patient;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ViewPatient extends ViewRecord
{
    protected static string $resource = PatientResource::class;

    /**
     * Header actions: invite to the portal, edit.
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('invite_to_portal')
                ->label(__('app.patients.invite_to_portal'))
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->visible(fn (Patient $record) => $record->portalUser()->doesntExist() && filled($record->email))
                ->requiresConfirmation()
                ->modalDescription(fn (Patient $record) => __('app.patients.invite_to_portal_help', ['email' => $record->email]))
                ->action(function (Patient $record) {
                    $user = $record->portalUser()->create([
                        'name' => $record->full_name,
                        'email' => $record->email,
                        'password' => bcrypt(Str::random(40)),
                        'email_verified_at' => null,
                    ]);

                    $record->setRelation('portalUser', $user);

                    $user->teams()->syncWithoutDetaching([
                        $record->team_id => ['role' => TeamRole::Member->value],
                    ]);
                    $user->update(['current_team_id' => $record->team_id]);

                    $status = Password::sendResetLink(['email' => $user->email]);

                    Notification::make()
                        ->title($status === Password::RESET_LINK_SENT
                            ? __('app.patients.invite_sent')
                            : __('app.patients.invite_failed'))
                        ->success()
                        ->send();
                }),
            EditAction::make(),
        ];
    }
}
