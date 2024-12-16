<?php

namespace App\Providers;

use App\Contracts\ProjectMaker;
use App\Services\Linux\MonolitProjectMaker;
use App\Services\Linux\TaskManager;
use App\Services\ProjectMakerContext;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class ProjectMakerContextServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProjectMaker::class, MonolitProjectMaker::class);

        $this->app->bind(MonolitProjectMaker::class, function (Application $app) {
            return new MonolitProjectMaker($app->make(TaskManager::class));
        });

        $this->app->singleton(ProjectMakerContext::class, function (Application $app) {
            return new ProjectMakerContext($app->make(ProjectMaker::class));
        });
    }
}
