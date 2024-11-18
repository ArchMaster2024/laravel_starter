<?php

namespace App\Providers;

use App\Services\Linux\TaskManager;
use App\Contracts\ProcessesManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class TaskManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TaskManager::class, function (Application $app) {
            return new TaskManager($app->make(ProcessesManager::class));
        });
    }
}
