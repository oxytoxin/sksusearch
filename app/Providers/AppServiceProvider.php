<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {}

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Relation::morphMap([
            'dv' => \App\Models\DisbursementVoucher::class,
            'lr' => \App\Models\LiquidationReport::class,
        ]);
        Model::unguard();
        Filament::registerRenderHook(
            'body.end',
            fn (): View => view('additional-scripts'),
        );

    }
}
