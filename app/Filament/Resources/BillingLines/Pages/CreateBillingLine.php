<?php

namespace App\Filament\Resources\BillingLines\Pages;

use App\Filament\Resources\BillingLines\BillingLineResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBillingLine extends CreateRecord
{
    protected static string $resource = BillingLineResource::class;
}
