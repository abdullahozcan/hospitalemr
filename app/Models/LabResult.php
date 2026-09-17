<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use App\Enums\LabResultFlag;
use Database\Factories\LabResultFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $team_id
 * @property int $lab_order_id
 * @property int $lab_test_id
 * @property float|null $value_numeric
 * @property string|null $value_text
 * @property LabResultFlag|null $flag
 * @property int|null $resulted_by
 * @property Carbon|null $resulted_at
 * @property int|null $reviewed_by
 * @property Carbon|null $reviewed_at
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read LabOrder $labOrder
 * @property-read LabTest $labTest
 * @property-read User|null $resultWriter
 * @property-read User|null $reviewer
 * @property-read bool $is_abnormal
 */
#[Fillable([
    'team_id',
    'lab_order_id',
    'lab_test_id',
    'value_numeric',
    'value_text',
    'flag',
    'resulted_by',
    'resulted_at',
    'reviewed_by',
    'reviewed_at',
    'notes',
])]
class LabResult extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<LabResultFactory> */
    use HasFactory;

    /**
     * The order this result belongs to.
     *
     * @return BelongsTo<LabOrder, $this>
     */
    public function labOrder(): BelongsTo
    {
        return $this->belongsTo(LabOrder::class);
    }

    /**
     * The test this result measures.
     *
     * @return BelongsTo<LabTest, $this>
     */
    public function labTest(): BelongsTo
    {
        return $this->belongsTo(LabTest::class);
    }

    /**
     * The staff member who recorded the result.
     *
     * @return BelongsTo<User, $this>
     */
    public function resultWriter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resulted_by');
    }

    /**
     * The doctor who reviewed the result.
     *
     * @return BelongsTo<User, $this>
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Whether the result is flagged as out of range.
     */
    public function getIsAbnormalAttribute(): bool
    {
        return $this->flag !== null
            && in_array($this->flag, [LabResultFlag::Low, LabResultFlag::High, LabResultFlag::Critical], true);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'flag' => LabResultFlag::class,
            'value_numeric' => 'decimal:3',
            'resulted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }
}
