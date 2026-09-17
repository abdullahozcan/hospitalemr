<?php

namespace App\Filament\Resources\LabOrders\Pages;

use App\Filament\Resources\LabOrders\LabOrderResource;
use App\Models\LabResult;
use Filament\Resources\Pages\CreateRecord;

class CreateLabOrder extends CreateRecord
{
    protected static string $resource = LabOrderResource::class;

    /**
     * The ordered test ids captured during form submission.
     *
     * @var list<int>
     */
    protected array $labTestIds = [];

    /**
     * Create an empty result row for each ordered test and default the status.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->labTestIds = $data['lab_test_ids'] ?? [];

        unset($data['lab_test_ids']);

        $data['status'] ??= 'ordered';

        if (empty($data['ordered_by'])) {
            $data['ordered_by'] = auth()->id();
        }

        return $data;
    }

    /**
     * Persist one empty lab result per ordered test.
     */
    protected function afterCreate(): void
    {
        $order = $this->getRecord();

        foreach ($this->labTestIds as $testId) {
            LabResult::create([
                'team_id' => $order->getAttribute('team_id'),
                'lab_order_id' => $order->getKey(),
                'lab_test_id' => $testId,
            ]);
        }
    }
}
