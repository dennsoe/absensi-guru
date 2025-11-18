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
        // Register Services Layer
        $this->app->singleton(\App\Services\IzinCutiService::class, function ($app) {
            return new \App\Services\IzinCutiService();
        });

        $this->app->singleton(\App\Services\LaporanService::class, function ($app) {
            return new \App\Services\LaporanService();
        });

        $this->app->singleton(\App\Services\MonitoringService::class, function ($app) {
            return new \App\Services\MonitoringService();
        });

        $this->app->singleton(\App\Services\NotifikasiService::class, function ($app) {
            return new \App\Services\NotifikasiService();
        });

        $this->app->singleton(\App\Services\StatistikService::class, function ($app) {
            return new \App\Services\StatistikService();
        });

        $this->app->singleton(\App\Services\ValidationService::class, function ($app) {
            return new \App\Services\ValidationService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register event listeners
        \Illuminate\Support\Facades\Event::listen(
            \App\Events\AbsensiCreated::class,
            [
                \App\Listeners\SendAbsensiNotification::class,
                \App\Listeners\UpdateRekapJamMengajar::class,
            ]
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\IzinApproved::class,
            \App\Listeners\SendIzinApprovedNotification::class
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\GuruPenggantiAssigned::class,
            \App\Listeners\NotifyGuruPengganti::class
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\NotificationSent::class,
            \App\Listeners\LogNotificationSent::class
        );
    }
}
