<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

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
        // lets register the helper function to get a list of active modules
        app()->singleton('getActiveModules', function ($app) {
            return function () {
                $modules = json_decode(File::get(base_path('modules_statuses.json')), true);
                return array_filter($modules, function ($status) {
                    return $status;
                });
            };
        });
    }
}
