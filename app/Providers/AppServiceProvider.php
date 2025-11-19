<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Domain\Contracts\ClientRepository;
use App\Domain\Contracts\ChambreRepository;
use App\Domain\Contracts\ServiceRepository;
use App\Domain\Contracts\PaiementRepository;
use App\Infrastructure\Services\EmailService;
use App\Domain\Services\EmailServiceInterface;
use App\Domain\Contracts\ReservationRepository;
use App\Domain\Contracts\TypeChambreRepository;
use App\Infrastructure\Repositories\EloquentClientRepository;
use App\Infrastructure\Repositories\EloquentChambreRepository;
use App\Infrastructure\Repositories\EloquentServiceRepository;
use App\Infrastructure\Repositories\EloquentPaiementRepository;
use App\Infrastructure\Repositories\EloquentReservationRepository;
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
        $this->app->bind(ChambreRepository::class,EloquentChambreRepository::class);
        $this->app->bind(ClientRepository::class,EloquentClientRepository::class);
        $this->app->bind(ReservationRepository::class,EloquentReservationRepository::class);
        $this->app->bind(EmailServiceInterface::class, EmailService::class);
        $this->app->bind(PaiementRepository::class,EloquentPaiementRepository::class);


        


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    Schema::disableForeignKeyConstraints();
    Schema::enableForeignKeyConstraints();
    }
}
