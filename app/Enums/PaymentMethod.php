<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum PaymentMethod: string implements HasColor, HasIcon, HasLabel
{
    case Cash = 'cash';
    case Card = 'card';
    case Transfer = 'transfer';
    case Insurance = 'insurance';

    public function getLabel(): string
    {
        return __('app.payment-methods.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Cash => 'success',
            self::Card => 'info',
            self::Transfer => 'primary',
            self::Insurance => 'warning',
        };
    }

    public function getIcon(): Heroicon
    {
        return match ($this) {
            self::Cash => Heroicon::OutlinedBanknotes,
            self::Card => Heroicon::OutlinedCreditCard,
            self::Transfer => Heroicon::OutlinedArrowsRightLeft,
            self::Insurance => Heroicon::OutlinedShieldCheck,
        };
    }
}
