<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use App\Enums\Gender;
use Database\Factories\PatientFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $team_id
 * @property string $mrn
 * @property string $first_name
 * @property string $last_name
 * @property Carbon|null $birth_date
 * @property Gender|null $gender
 * @property string|null $national_id
 * @property string|null $blood_type
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property string|null $city
 * @property string|null $district
 * @property string|null $emergency_contact_name
 * @property string|null $emergency_contact_phone
 * @property string|null $emergency_contact_relation
 * @property bool $is_deceased
 * @property Carbon|null $deceased_at
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string $full_name
 * @property-read int|null $age
 * @property-read float $balance
 * @property-read Collection<int, InsurancePolicy> $insurancePolicies
 * @property-read Collection<int, PatientDocument> $documents
 * @property-read Collection<int, Encounter> $encounters
 * @property-read Collection<int, Allergy> $allergies
 * @property-read Collection<int, Immunization> $immunizations
 * @property-read Collection<int, BillingLine> $billingLines
 * @property-read Collection<int, Payment> $payments
 * @property-read Collection<int, LabOrder> $labOrders
 * @property-read Collection<int, Appointment> $appointments
 * @property-read Collection<int, Prescription> $prescriptions
 * @property-read User|null $portalUser
 */
#[Fillable([
    'team_id',
    'mrn',
    'first_name',
    'last_name',
    'birth_date',
    'gender',
    'national_id',
    'blood_type',
    'phone',
    'email',
    'address',
    'city',
    'district',
    'emergency_contact_name',
    'emergency_contact_phone',
    'emergency_contact_relation',
    'is_deceased',
    'deceased_at',
    'notes',
])]
class Patient extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<PatientFactory> */
    use HasFactory, SoftDeletes;

    /**
     * Bootstrap the model and its traits.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Patient $patient) {
            if (empty($patient->mrn)) {
                $patient->mrn = static::generateUniqueMrn();
            }
        });
    }

    /**
     * Generate a unique medical record number.
     */
    public static function generateUniqueMrn(): string
    {
        do {
            $mrn = 'MRN-'.Str::upper(Str::random(8));
        } while (static::withTrashed()->where('mrn', $mrn)->exists());

        return $mrn;
    }

    /**
     * The patient's full name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * The patient's age in years.
     */
    public function getAgeAttribute(): ?int
    {
        return $this->birth_date?->age;
    }

    /**
     * The patient's insurance policies.
     *
     * @return HasMany<InsurancePolicy, $this>
     */
    public function insurancePolicies(): HasMany
    {
        return $this->hasMany(InsurancePolicy::class);
    }

    /**
     * The patient's documents.
     *
     * @return HasMany<PatientDocument, $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(PatientDocument::class);
    }

    /**
     * The patient's encounters.
     *
     * @return HasMany<Encounter, $this>
     */
    public function encounters(): HasMany
    {
        return $this->hasMany(Encounter::class);
    }

    /**
     * The patient's known allergies.
     *
     * @return HasMany<Allergy, $this>
     */
    public function allergies(): HasMany
    {
        return $this->hasMany(Allergy::class);
    }

    /**
     * The patient's immunization records.
     *
     * @return HasMany<Immunization, $this>
     */
    public function immunizations(): HasMany
    {
        return $this->hasMany(Immunization::class);
    }

    /**
     * The patient's billing lines.
     *
     * @return HasMany<BillingLine, $this>
     */
    public function billingLines(): HasMany
    {
        return $this->hasMany(BillingLine::class);
    }

    /**
     * The patient's payments.
     *
     * @return HasMany<Payment, $this>
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * The patient's laboratory orders.
     *
     * @return HasMany<LabOrder, $this>
     */
    public function labOrders(): HasMany
    {
        return $this->hasMany(LabOrder::class);
    }

    /**
     * The patient's appointments.
     *
     * @return HasMany<Appointment, $this>
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * The patient's prescriptions.
     *
     * @return HasMany<Prescription, $this>
     */
    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * The patient's outstanding balance.
     */
    public function getBalanceAttribute(): float
    {
        $billed = (float) $this->billingLines()
            ->get()
            ->sum('total');

        $paid = (float) $this->payments()->sum('amount');

        return round($billed - $paid, 2);
    }

    /**
     * The portal account linked to this patient, if any.
     *
     * @return HasOne<User, $this>
     */
    public function portalUser(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * Whether the patient has a portal account.
     */
    public function hasPortalAccess(): bool
    {
        return $this->portalUser()->exists();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'deceased_at' => 'date',
            'gender' => Gender::class,
            'is_deceased' => 'boolean',
        ];
    }
}
