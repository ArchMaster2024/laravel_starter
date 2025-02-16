<?php

use App\Services\Linux\MonolitProjectMaker;
use App\Services\Linux\TaskManager;
use Mockery;

describe('monolitProjectMaker', function () {
    it('execute method not allow empty array', function () {
        $taskManager = Mockery::mock(TaskManager::class);
        $monolitProjectMaker = new MonolitProjectMaker($taskManager);
        $result = $monolitProjectMaker->execute([], 'project-name');
        expect($result)->toBeNull();
    });

    it('execute method, calls taskInSelectedLocation with selected type dependency in one step', function () {
        $taskManager = Mockery::mock(TaskManager::class);
        $monolitProjectMaker = new MonolitProjectMaker($taskManager);
        $result = $monolitProjectMaker->execute(, './');
        expect($result)->toBeNull();
    });
});
