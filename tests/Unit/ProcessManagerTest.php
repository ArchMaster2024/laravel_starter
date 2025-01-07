<?php

use App\Services\Linux\ProcessManager;
use Mockery;
use Illuminate\Contracts\Process\ProcessResult;

it('Process manager synchronous method run command', function () {
    $processResult = Mockery::mock(ProcessResult::class);
    Process::shouldReceive('run')->once()->with('ls -l')->andReturn($processResult);
    $processManager = new ProcessManager();
    $result = $processManager->synchronous('ls -l');
    expect($result)->toBe($processResult);
});

it('Process manager interact with system method runs command', function () {
    $processResult = Mockery::mock(ProcessResult::class);
    Process::shouldReceive('forever')->once()->andReturnSelf();
    Process::shouldReceive('tty')->once()->andReturnSelf();
    Process::shouldReceive('run')->once()->with('ls -l')->andReturn($processResult);
    $processManager = new ProcessManager();
    $result = $processManager->interactWithSystem('ls -l');
    expect($result)->toBe($processResult);
});

test('Process manager work in other directory method runs command', function () {
    $processResult = Mockery::mock(ProcessResult::class);
    Process::shouldReceive('path')->once()->andReturnSelf();
    Process::shouldReceive('run')->once()->with('ls -l')->andReturn($processResult);
    $processManager = new ProcessManager();
    $result = $processManager->workInOtherDirectory('ls -l', './');
    expect($result)->toBe($processResult);
});
