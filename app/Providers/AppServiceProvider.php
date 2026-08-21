<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array<string, string>
     */
    public $bindings = [];

    /**
     * All of the container singletons that should be registered.
     *
     * @var array<string, string>
     */
    public $singletons = [];

    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureModels();
        $this->configureDatabaseSecurity();
        $this->configureUrl();
        $this->configureDates();
        $this->configurePasswords();
        $this->configureRateLimiting();
    }

    /**
     * Configure Eloquent strictness and behavior.
     */
    private function configureModels(): void
    {
        // Enforce strict mode in non-production environments
        // Prevents N+1 queries (preventLazyLoading), silently discarding attributes, and missing attributes
        Model::shouldBeStrict( ! $this->app->isProduction());
    }

    /**
     * Prohibit destructive database commands on production environments.
     */
    private function configureDatabaseSecurity(): void
    {
        DB::prohibitDestructiveCommands($this->app->isProduction());
    }

    /**
     * Enforce HTTPS URL scheme in non-local environments.
     */
    private function configureUrl(): void
    {
        if ( ! $this->app->isLocal()) {
            URL::forceScheme('https');
        }
    }

    /**
     * Use Immutable Carbon dates for thread-safety and side-effect prevention.
     */
    private function configureDates(): void
    {
        Date::use(CarbonImmutable::class);
    }

    /**
     * Define default password validation rules.
     */
    private function configurePasswords(): void
    {
        Password::defaults(function () {
            $rule = Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols();

            return $this->app->isProduction()
                ? $rule->uncompromised()
                : $rule;
        });
    }

    /**
     * Configure rate limiters for the application.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('api', fn(Request $request) => Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()));

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = mb_strtolower((string) $request->input('email', '')) . '|' . $request->ip();

            return Limit::perMinute(5)->by($throttleKey)->response(fn() => response()->json([
                'success' => false,
                'message' => 'Too many login attempts. Please try again in 1 minute.',
                'code' => 'AUTH_RATE_LIMITED',
            ], 429));
        });

        RateLimiter::for('sensitive', fn(Request $request) => Limit::perMinute(10)->by($request->user()?->id ?: $request->ip()));
    }
}
