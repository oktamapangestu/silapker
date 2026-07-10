<?php

namespace App\Providers;

use App\Services\CentralApiClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CentralApiClient::class, fn () => new CentralApiClient(
            baseUrl: rtrim(config('services.central_api.base_url'), '/'),
            systemName: config('services.central_api.system_name'),
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
