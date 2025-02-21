<?php

namespace App\Providers;

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
        if (env('APP_ENV') == 'production') {
            $this->app['request']->server->set('HTTPS', true);
        }
        $keyFilePath = config('services.google.credentials');
        if (file_exists($keyFilePath)) {
            chmod($keyFilePath, 0644); 
            putenv("GOOGLE_APPLICATION_CREDENTIALS={$keyFilePath}");
        }
    }
}
