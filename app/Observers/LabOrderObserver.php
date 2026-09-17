<?php

namespace App\Observers;

use App\Enums\LabOrderStatus;
use App\Jobs\SyncLabOrderToThs;
use App\Models\LabOrder;

class LabOrderObserver
{
    /**
     * Queue an e-Nabız submission when all results are reviewed.
     */
    public function updated(LabOrder $order): void
    {
        if (! $order->wasChanged('status')) {
            return;
        }

        if ($order->status !== LabOrderStatus::Reviewed
            || $order->getOriginal('status') !== LabOrderStatus::Resulted) {
            return;
        }

        SyncLabOrderToThs::dispatch($order);
    }
}
