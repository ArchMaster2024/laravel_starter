<?php

namespace App\Services\Linux;

use App\Contracts\ProcessesManager;

use function Laravel\Prompts\note;
use function Laravel\Prompts\error;

class TaskManager
{
    public function __construct(private ProcessesManager $process)
    {
    }

    /**
     * Method for executing a synchronous task,
     *
     * @param string $command
     */
    public function simpleSynchronousTask(string $command): bool
    {
        $taskToRun = $this->process->synchronous($command);
        if ($taskToRun->failed()) {
            error($taskToRun->errorOutput());
            return false;
        }
        if ($taskToRun->successful()) {
            note($taskToRun->output());
            return true;
        }
    }

    /**
     * Method for run task in selected directory
     *
     * @param string $command
     * @param string $location
     */
    public function taskInSelectedLocation(string $command, ?string $location): bool
    {
        $taskToRun = $this->process->workInOtherDirectory($command, $location);
        if ($taskToRun->failed()) {
            error($taskToRun->errorOutput());
            return false;
        }
        if ($taskToRun->successful()) {
            note($taskToRun->output());
            return true;
        }
    }

    public function workWithTaskMacroOfLaravelZero(string $taskName, $closure, object $laravelZero): void
    {
        $laravelZero->task($taskName, $closure);
    }
}
