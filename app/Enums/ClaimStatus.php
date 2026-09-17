<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ClaimStatus: string implements HasColor, HasLabel
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Approved = 'approved';
    case Denied = 'denied';
    case Paid = 'paid';

    public function getLabel(): string
    {
        return __('app.claim-statuses.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Submitted => 'info',
            self::Approved => 'success',
            self::Denied => 'danger',
            self::Paid => 'primary',
        };
    }

    /**
     * Get the statuses this status may transition to.
     *
     * @return list<ClaimStatus>
     */
    public function nextStatuses(): array
    {
        return match ($this) {
            self::Draft => [self::Submitted],
            self::Submitted => [self::Approved, self::Denied],
            self::Approved => [self::Paid],
            self::Denied => [self::Draft],
            self::Paid => [],
        };
    }
}
