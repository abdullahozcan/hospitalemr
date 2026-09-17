<?php

namespace App\Fhir;

use Illuminate\Database\Eloquent\Model;

/**
 * Maps Eloquent models to FHIR R4 resource arrays.
 */
abstract class FhirMapper
{
    /**
     * Map the model to one or more FHIR R4 resource arrays.
     *
     * @return array<string, mixed>|list<array<string, mixed>>
     */
    abstract public function toFhir(Model $model): array;

    /**
     * Build the common resource envelope.
     *
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    protected function baseResource(string $resourceType, string|int|null $id, array $extra = []): array
    {
        return array_merge([
            'resourceType' => $resourceType,
            'id' => (string) $id,
            'meta' => [
                'profile' => [$this->profile()],
            ],
        ], $extra);
    }

    /**
     * The FHIR resource type the mapper produces.
     */
    abstract protected function resourceType(): string;

    /**
     * The FHIR profile this resource claims conformance to.
     */
    abstract protected function profile(): string;

    /**
     * Map a date value to FHIR format.
     */
    protected function fhirDate(?\DateTimeInterface $date): ?string
    {
        return $date?->format('Y-m-d');
    }

    /**
     * Map a datetime value to FHIR instant format.
     */
    protected function fhirDateTime(?\DateTimeInterface $datetime): ?string
    {
        return $datetime?->format(DATE_ATOM);
    }
}
