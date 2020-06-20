<?php

namespace App\Providers;

use App\ChatDrivers\MyViberDriver;
use BotMan\BotMan\Drivers\DriverManager;
use Illuminate\Support\ServiceProvider;
use TheArdent\Drivers\Viber\ViberDriver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DriverManager::unloadDriver(ViberDriver::class);
        DriverManager::loadDriver(MyViberDriver::class);
        \Log::info(json_encode(DriverManager::getAvailableDrivers()));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
