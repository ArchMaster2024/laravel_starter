<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use App\Services\LaravelInstaller;
use App\Services\Linux\TaskManager;

class LaravelInstallerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LaravelInstaller::class, function (Application $app) {
            return new LaravelInstaller($app->make(TaskManager::class));
        });
    }
}
