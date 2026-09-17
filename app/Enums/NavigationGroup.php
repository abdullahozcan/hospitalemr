<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum NavigationGroup: string implements HasLabel
{
    case Organization = 'organization';
    case Patients = 'patients';
    case Clinical = 'clinical';
    case Billing = 'billing';
    case Laboratory = 'laboratory';
    case Reports = 'reports';
    case Settings = 'settings';

    public function getLabel(): string
    {
        return __('app.navigation.'.$this->value);
    }
}
