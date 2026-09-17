<?php

namespace App\Services;

use App\Models\Drug;
use App\Models\Patient;
use Illuminate\Support\Collection;

/**
 * Flags prescriptions that may interact with the patient's known allergies.
 *
 * The matching is intentionally simple: a drug is flagged when the patient's
 * allergen, the drug name, or the drug's active ingredient share a term.
 */
class DrugInteractionService
{
    /**
     * Check the patient's allergies against the given drugs.
     *
     * @param  list<int>|Collection<int, int|Drug>  $drugs
     * @return Collection<int, array{allergen: string, drug: string}>
     */
    public function check(Patient $patient, array|Collection $drugs): Collection
    {
        $allergies = $patient->allergies()
            ->pluck('allergen')
            ->map(fn (string $allergen) => $this->normalize($allergen));

        if ($allergies->isEmpty()) {
            return collect();
        }

        return collect($drugs)
            ->map(fn (int|Drug $drug) => $drug instanceof Drug ? $drug : Drug::query()->find($drug))
            ->filter()
            ->map(function (Drug $drug) use ($allergies) {
                $terms = collect([
                    $this->normalize($drug->name),
                    $this->normalize((string) $drug->active_ingredient),
                ])->filter();

                foreach ($allergies as $allergen) {
                    foreach ($terms as $term) {
                        if (str_contains($term, $allergen) || str_contains($allergen, $term)) {
                            return ['allergen' => $allergen, 'drug' => $drug->name];
                        }
                    }
                }

                return null;
            })
            ->filter()
            ->values();
    }

    /**
     * Normalize a term for loose comparison.
     */
    protected function normalize(string $term): string
    {
        return mb_strtolower(trim($term));
    }
}
