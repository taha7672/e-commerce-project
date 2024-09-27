<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::defaultView('vendor.pagination.bootstrap-5');
		require_once base_path('app/helpers.php');

        Blade::directive('price_symbol', fn() => "{{ priceSymbol() }}");
        Blade::directive('price', fn($exp) => "{{ toPrice($exp) }}");
        Blade::directive('currency', fn($exp) => "{{ toCurrency($exp) }}");
    }
}
