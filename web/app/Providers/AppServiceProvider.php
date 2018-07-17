<?php

namespace App\Providers;

use App\Dogsy\Concerns\ServiceContract;
use App\Dogsy\Services\DogsyOperationsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Присваиваем текущую реализацию сервиса Dogsy
         */
        $this->app->bind(ServiceContract::class,DogsyOperationsService::class);
    }
}
