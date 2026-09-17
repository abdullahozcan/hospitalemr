<?php

namespace App\Rules;

use App\Models\Appointment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;

/**
 * Prevents two active appointments for the same provider from overlapping.
 */
class NoOverlappingAppointment implements ValidationRule
{
    public function __construct(
        protected ?int $providerId,
        protected mixed $endsAt,
        protected ?int $exceptId = null,
    ) {}

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->providerId === null || empty($value) || empty($this->endsAt)) {
            return;
        }

        $startsAt = Carbon::parse($value);
        $endsAt = Carbon::parse($this->endsAt);

        if ($endsAt->lessThanOrEqualTo($startsAt)) {
            return;
        }

        if (Appointment::providerHasOverlap($this->providerId, $startsAt, $endsAt, $this->exceptId)) {
            $fail(__('app.validation.appointment_overlap'));
        }
    }
}
