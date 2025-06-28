<?php

namespace BoukjijTarik\WooSales;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;

class WooSalesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/wooSales.php', 'wooSales');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'wooSales');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        
        $this->publishes([
            __DIR__ . '/../config/wooSales.php' => config_path('wooSales.php'),
        ], 'wooSales-config');
        
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/wooSales'),
        ], 'wooSales-views');
    }
} 