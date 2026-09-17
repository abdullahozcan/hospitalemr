<?php

namespace App\Providers;

use App\Models\Encounter;
use App\Models\LabOrder;
use App\Models\Prescription;
use App\Observers\EncounterObserver;
use App\Observers\LabOrderObserver;
use App\Observers\PrescriptionObserver;
use App\Ths\MockThsService;
use App\Ths\SoapThsService;
use App\Ths\ThsServiceInterface;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ThsServiceInterface::class,
            fn () => config('ths.driver') === 'soap'
                ? new SoapThsService
                : new MockThsService,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        Encounter::observe(EncounterObserver::class);
        LabOrder::observe(LabOrderObserver::class);
        Prescription::observe(PrescriptionObserver::class);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
