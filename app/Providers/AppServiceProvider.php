<?php

namespace App\Providers;

use App\Repositories\AgentRepository;
use App\Repositories\ApiRequestRepository;
use App\Repositories\Interfaces\AgentRepositoryInterface;
use App\Repositories\Interfaces\ApiRequestRepositoryInterface;
use App\Repositories\Interfaces\PropertyRepositoryInterface;
use App\Repositories\Interfaces\PropertyTypeRepositoryInterface;
use App\Repositories\Interfaces\PropertyUpdateRepositoryInterface;
use App\Repositories\PropertyRepository;
use App\Repositories\PropertyTypeRepository;
use App\Repositories\PropertyUpdateRepository;
use App\Services\ExternalRequest\ApiRequestServiceInterface;
use App\Services\ExternalRequest\GuzzleApiRequestService;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Sanctum::ignoreMigrations();

        //Repository bindings
        $this->app->bind(AgentRepositoryInterface::class, AgentRepository::class);
        $this->app->bind(ApiRequestRepositoryInterface::class, ApiRequestRepository::class);
        $this->app->bind(PropertyTypeRepositoryInterface::class, PropertyTypeRepository::class);
        $this->app->bind(PropertyRepositoryInterface::class, PropertyRepository::class);
        $this->app->bind(PropertyUpdateRepositoryInterface::class, PropertyUpdateRepository::class);

        $this->app->bind(ApiRequestServiceInterface::class, GuzzleApiRequestService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
