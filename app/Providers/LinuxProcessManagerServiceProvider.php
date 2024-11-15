<?php

namespace App\Providers;

use App\Contracts\ProcessesManager;
use App\Services\Linux\ProcessManager;
use Illuminate\Support\ServiceProvider;

class LinuxProcessManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProcessesManager::class, ProcessManager::class);
    }
}
