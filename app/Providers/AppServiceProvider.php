<?php

namespace App\Providers;

use App\Repositories\TodoRepositoryImpl;
use App\Repositories\TodoRepositoryInterface;
use App\Services\ColorMappingServiceImpl;
use App\Services\ColorMappingServiceInterface;
use App\Services\StringServicesImpl;
use App\Services\StringServicesInterface;
use App\Services\TodoServicesImpl;
use App\Services\TodoServicesInterface;
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
        $this->app->bind(TodoRepositoryInterface::class, TodoRepositoryImpl::class);
        $this->app->bind(TodoServicesInterface::class, TodoServicesImpl::class);
        $this->app->bind(StringServicesInterface::class, StringServicesImpl::class);
        $this->app->bind(ColorMappingServiceInterface::class, ColorMappingServiceImpl::class);
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
