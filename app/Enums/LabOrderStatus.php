<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum LabOrderStatus: string implements HasColor, HasLabel
{
    case Ordered = 'ordered';
    case Collected = 'collected';
    case InLaboratory = 'in_laboratory';
    case Resulted = 'resulted';
    case Reviewed = 'reviewed';
    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return __('app.lab-order-statuses.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Ordered => 'info',
            self::Collected => 'warning',
            self::InLaboratory => 'primary',
            self::Resulted => 'success',
            self::Reviewed => 'success',
            self::Cancelled => 'gray',
        };
    }

    /**
     * Get the statuses this status may transition to.
     *
     * @return list<LabOrderStatus>
     */
    public function nextStatuses(): array
    {
        return match ($this) {
            self::Ordered => [self::Collected, self::InLaboratory, self::Cancelled],
            self::Collected => [self::InLaboratory, self::Cancelled],
            self::InLaboratory => [self::Resulted],
            self::Resulted => [self::Reviewed],
            self::Reviewed, self::Cancelled => [],
        };
    }
}
