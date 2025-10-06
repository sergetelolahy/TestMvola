<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Contracts\ServiceRepository;
use App\Domain\Contracts\TypeChambreRepository;
use App\Infrastructure\Repositories\EloquentServiceRepository;
use App\Infrastructure\Repositories\EloquentTypeChambreRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ServiceRepository::class, EloquentServiceRepository::class);
        $this->app->bind(TypeChambreRepository::class,EloquentTypeChambreRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
