<?php

namespace App\Jobs;

use App\Models\Encounter;
use App\Models\IntegrationLog;
use App\Models\LabOrder;
use App\Models\Prescription;
use App\Ths\ThsResponse;
use App\Ths\ThsServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

/**
 * Base queue job that synchronizes a clinical record with the THS.
 */
abstract class ThsSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries;

    /**
     * The seconds between retries.
     *
     * @var list<int>
     */
    public array $backoff;

    /**
     * Create the job.
     */
    public function __construct()
    {
        $this->tries = (int) config('ths.queue.tries', 3);
        $this->backoff = config('ths.queue.backoff_seconds', [60, 300, 900]);
    }

    /**
     * Execute the job.
     */
    public function handle(ThsServiceInterface $ths): void
    {
        if (! config('ths.enabled', true)) {
            return;
        }

        $this->record($this->perform($ths));
    }

    /**
     * Record the outcome on the integration log.
     */
    protected function record(ThsResponse $response): void
    {
        $this->openLog()->forceFill([
            'status' => $response->success ? IntegrationLog::STATUS_SUCCESS : IntegrationLog::STATUS_FAILED,
            'code' => $response->code,
            'message' => $response->message,
            'response_payload' => $response->raw,
            'attempts' => 1,
            'sent_at' => $response->success ? now() : null,
        ])->save();

        // Business failures (data validation) are terminal: the log records
        // them and a manual retry is available from the panel. Transport
        // exceptions still retry via $tries.
    }

    /**
     * Create the log entry for this attempt.
     */
    protected function openLog(): IntegrationLog
    {
        return IntegrationLog::query()->create([
            'team_id' => $this->getSubject()->team_id,
            'service' => $this->getOperation() === 'sendPrescription'
                ? IntegrationLog::SERVICE_SGK
                : IntegrationLog::SERVICE_ENABIZ,
            'operation' => $this->getOperation(),
            'subject_type' => $this->getSubject()->getMorphClass(),
            'subject_id' => $this->getSubject()->getKey(),
            'status' => IntegrationLog::STATUS_PENDING,
            'attempts' => 0,
        ]);
    }

    /**
     * Record the final failure after all retries.
     */
    public function failed(Throwable $exception): void
    {
        $log = IntegrationLog::query()
            ->where('subject_type', $this->getSubject()->getMorphClass())
            ->where('subject_id', $this->getSubject()->getKey())
            ->where('operation', $this->getOperation())
            ->latest()
            ->first();

        $log?->forceFill([
            'status' => IntegrationLog::STATUS_FAILED,
            'message' => substr($exception->getMessage(), 0, 2000),
            'attempts' => $log->attempts + 1,
        ])->save();
    }

    /**
     * Run the THS operation for the subject.
     */
    abstract protected function perform(ThsServiceInterface $ths): ThsResponse;

    /**
     * The subject model being synchronized.
     */
    abstract public function getSubject(): Encounter|LabOrder|Prescription;

    /**
     * The THS operation name.
     */
    abstract public function getOperation(): string;
}
