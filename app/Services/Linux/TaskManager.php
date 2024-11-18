<?php

namespace App\Services\Linux;

use App\Contracts\ProcessesManager;

class TaskManager
{
    public function __construct(private ProcessesManager $process)
    {
    }

    public function simpleSynchronousTask(string $command): bool
    {
        $taskToExecute = $this->process->synchronous($command);
        if ($taskToExecute->failed()) {
            return false;
        }
        return true;
    }

    public function taskInSelectedLocation(string $command, ?string $location): bool
    {
        $taskToExecute = $this->process->workInOtherDirectory($command, $location);
        if ($taskToExecute->failed()) {
            return false;
        }
        return true;
    }
}
