<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum AppointmentStatus: string implements HasColor, HasIcon, HasLabel
{
    case Scheduled = 'scheduled';
    case CheckedIn = 'checked_in';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case NoShow = 'no_show';
    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return __('app.appointment-statuses.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Scheduled => 'info',
            self::CheckedIn => 'warning',
            self::InProgress => 'primary',
            self::Completed => 'success',
            self::NoShow => 'danger',
            self::Cancelled => 'gray',
        };
    }

    public function getIcon(): Heroicon
    {
        return match ($this) {
            self::Scheduled => Heroicon::OutlinedClock,
            self::CheckedIn => Heroicon::OutlinedArrowRightStartOnRectangle,
            self::InProgress => Heroicon::OutlinedHeart,
            self::Completed => Heroicon::OutlinedCheckCircle,
            self::NoShow => Heroicon::OutlinedXCircle,
            self::Cancelled => Heroicon::OutlinedNoSymbol,
        };
    }

    /**
     * Get the statuses this status may transition to.
     *
     * @return list<AppointmentStatus>
     */
    public function nextStatuses(): array
    {
        return match ($this) {
            self::Scheduled => [self::CheckedIn, self::InProgress, self::Completed, self::NoShow, self::Cancelled],
            self::CheckedIn => [self::InProgress, self::Completed, self::NoShow],
            self::InProgress => [self::Completed, self::NoShow],
            self::NoShow => [self::Scheduled],
            self::Completed, self::Cancelled => [],
        };
    }

    /**
     * The statuses considered "waiting" on the flow board.
     *
     * @return list<AppointmentStatus>
     */
    public static function waitingStatuses(): array
    {
        return [self::Scheduled, self::CheckedIn, self::InProgress];
    }
}
