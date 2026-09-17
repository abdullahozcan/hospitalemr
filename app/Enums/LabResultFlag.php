<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum LabResultFlag: string implements HasColor, HasLabel
{
    case Normal = 'normal';
    case Low = 'low';
    case High = 'high';
    case Critical = 'critical';

    public function getLabel(): string
    {
        return __('app.lab-result-flags.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Normal => 'success',
            self::Low => 'warning',
            self::High => 'warning',
            self::Critical => 'danger',
        };
    }
}
