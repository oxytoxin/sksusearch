<?php

    namespace App\Providers;

    use Filament\Support\Colors\Color;
    use Filament\Support\Facades\FilamentColor;
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
        public function register()
        {
        }

        /**
         * Bootstrap any application services.
         *
         * @return void
         */
        public function boot()
        {
            FilamentColor::register([
                'primary' => Color::hex('#0c6600'),
            ]);

            Schema::defaultStringLength(191);
            Relation::morphMap([
                'dv' => \App\Models\DisbursementVoucher::class,
                'lr' => \App\Models\LiquidationReport::class,
                'to' => \App\Models\TravelOrder::class,
            ]);
            Model::unguard();
        }
    }
