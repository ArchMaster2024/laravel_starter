<?php

use App\Services\LaravelInstaller;
use App\Services\Linux\TaskManager;
use Mockery;

it('Laravel installer install method call task manager', function () {
    $taskManager = Mockery::mock(TaskManager::class);
    $taskManager->expects('workWithTaskMacroOfLaravelZero')->once()->with(
        'Installing Laravel', Mockery::any(), Mockery::any()
    );
    $laravelInstaller = new LaravelInstaller($taskManager);
    $laravelInstaller->install('project-name', Mockery::mock());
    $taskManager->shouldHaveReceived('workWithTaskMacroOfLaravelZero');
});
