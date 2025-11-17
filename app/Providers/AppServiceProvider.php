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
