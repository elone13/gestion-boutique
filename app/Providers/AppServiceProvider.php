<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Commande;
use App\Observers\CommandeObserver;

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
        Commande::observe(CommandeObserver::class);
    }
}
