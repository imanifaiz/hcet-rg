<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Response;

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
        Vite::prefetch(concurrency: 3);

        Response::macro('banner', function ($message): Response {
            return $this->with('flash', [
                'bannerStyle' => 'success',
                'banner' => $message,
            ]);
        });

        Response::macro('infoBanner', function ($message): Response {
            return $this->with('flash', [
                'bannerStyle' => 'info',
                'banner' => $message,
            ]);
        });

        Response::macro('warningBanner', function ($message): Response {
            return $this->with('flash', [
                'bannerStyle' => 'warning',
                'banner' => $message,
            ]);
        });

        Response::macro('dangerBanner', function ($message): Response {
            return $this->with('flash', [
                'bannerStyle' => 'error',
                'banner' => $message,
            ]);
        });
    }
}
