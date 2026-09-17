<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AllergySeverity: string implements HasColor, HasLabel
{
    case Mild = 'mild';
    case Moderate = 'moderate';
    case Severe = 'severe';
    case Anaphylactic = 'anaphylactic';

    public function getLabel(): string
    {
        return __('app.allergy-severities.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Mild => 'success',
            self::Moderate => 'warning',
            self::Severe => 'danger',
            self::Anaphylactic => 'danger',
        };
    }
}
