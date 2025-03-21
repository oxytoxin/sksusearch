<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
            Route::middleware('web')
                ->group(base_path('routes/custom/requisitioner.php'));
            Route::middleware('web')
                ->group(base_path('routes/custom/offices.php'));
            Route::middleware('web')
                ->group(base_path('routes/custom/signatory.php'));
            Route::middleware('web')
                ->group(base_path('routes/custom/icu.php'));
            Route::middleware('web')
                ->group(base_path('routes/custom/accounting.php'));
            Route::middleware('web')
                ->group(base_path('routes/custom/pcv.php'));
            Route::middleware('web')
                ->group(base_path('routes/custom/cashier.php'));
            Route::middleware('web')
                ->group(base_path('routes/custom/archiver.php'));
            Route::middleware('web')
                ->group(base_path('routes/custom/oic.php'));
            Route::middleware('web')
                ->group(base_path('routes/custom/motorpool.php'));
            Route::middleware('web')
                ->group(base_path('routes/custom/wfp.php'));
            Route::middleware('web')
                ->group(base_path('routes/custom/realtimenotification.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
