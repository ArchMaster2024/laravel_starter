<?php

use App\Services\Linux\TaskManager;
use App\Contracts\ProcessesManager;
use Illuminate\Contracts\Process\ProcessResult;
use Mockery;

describe('simpleSynchronousTask' , function () {
    it('method runs command', function () {
        $processResult = Mockery::mock(ProcessResult::class);
        $processResult->expects('failed')->once()->andReturn(false);
        $processResult->expects('successful')->once()->andReturn(true);
        $processResult->expects('output')->once()->andReturn('');
        $processManager = Mockery::mock(ProcessesManager::class);
        $processManager->expects('synchronous')->once()->with('ls -l')->andReturn($processResult);
        $taskManager = new TaskManager($processManager);
        $result = $taskManager->simpleSynchronousTask('ls -l');
        expect($result)->toBe(true);
    });

    it('method return false if command fails', function () {
        $processResult = Mockery::mock(ProcessResult::class);
        $processResult->expects('failed')->once()->andReturn(true);
        $processResult->expects('errorOutput')->once()->andReturn('');
        $processManager = Mockery::mock(ProcessesManager::class);
        $processManager->expects('synchronous')->once()->with('false || echo It failed.')->andReturn($processResult);
        $taskManager = new TaskManager($processManager);
        $result = $taskManager->simpleSynchronousTask('false || echo It failed.');
        expect($result)->toBe(false);
    });
});

describe('taskInSelectedLocation', function () {
    it ('method run command', function () {
        $processResult = Mockery::mock(ProcessResult::class);
        $processResult->expects('failed')->once()->andReturn(false);
        $processResult->expects('successful')->once()->andReturn(true);
        $processResult->expects('output')->once()->andReturn('');
        $processManager = Mockery::mock(ProcessesManager::class);
        $processManager->expects('workInOtherDirectory')->once()->with('ls -l', './')->andReturn($processResult);
        $taskManager = new TaskManager($processManager);
        $result = $taskManager->taskInSelectedLocation('ls -l', './');
        expect($result)->toBe(true);
    });

    it ('method return false if command fails', function () {
        $processResult = Mockery::mock(ProcessResult::class);
        $processResult->expects('failed')->once()->andReturn(true);
        $processResult->expects('errorOutput')->once()->andReturn('');
        $processManager = Mockery::mock(ProcessesManager::class);
        $processManager->expects('workInOtherDirectory')->once()->with('false || echo It failed.', './')->andReturn($processResult);
        $taskManager = new TaskManager($processManager);
        $result = $taskManager->taskInSelectedLocation('false || echo It failed.', './');
        expect($result)->toBe(false);
    });
});
