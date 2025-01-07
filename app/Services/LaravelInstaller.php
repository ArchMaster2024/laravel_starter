<?php

namespace App\Services;

use App\Services\Linux\TaskManager;

class LaravelInstaller
{
    public function __construct(private TaskManager $taskManager)
    {
    }

    /**
     * Method for installing a new laravel project in selected folder (project name)
     *
     * @param string $projectName
     * @param object $laravelZero
     */
    public function install(string $projectName, object $laravelZero): void
    {
        $this->taskManager->workWithTaskMacroOfLaravelZero("Installing Laravel", function () use ($projectName) {
            return $this->taskManager->simpleSynchronousTask("composer create-project laravel/laravel $projectName");
        }, $laravelZero);
    }
}
