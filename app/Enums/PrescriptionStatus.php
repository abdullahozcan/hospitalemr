<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PrescriptionStatus: string implements HasColor, HasLabel
{
    case Draft = 'draft';
    case Active = 'active';
    case Dispensed = 'dispensed';
    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return __('app.prescription-statuses.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Active => 'primary',
            self::Dispensed => 'success',
            self::Cancelled => 'danger',
        };
    }

    /**
     * Get the statuses this status may transition to.
     *
     * @return list<PrescriptionStatus>
     */
    public function nextStatuses(): array
    {
        return match ($this) {
            self::Draft => [self::Active, self::Cancelled],
            self::Active => [self::Dispensed, self::Cancelled],
            self::Dispensed, self::Cancelled => [],
        };
    }
}
