<?php

namespace App\Services\Linux;

use App\Contracts\ProcessesManager;
use Illuminate\Contracts\Process\ProcessResult;
use Illuminate\Support\Facades\Process;

class ProcessManager implements ProcessesManager
{
    /**
     * This method work like synchronous, their function is very simple
     * run simple and quick command of your operative systems
     *
     * @param string $command
     * @return ProcessResult
     */
    public function synchronous(string $command): ProcessResult
    {
        return Process::run($command);
    }

    /**
     * Method for interact with the os from the application
     *
     * @param string $command
     * @return ProcessResult
     */
    public function interactWithSystem(string $command): ProcessResult
    {
        return Process::forever()->tty()->run($command);
    }

    /**
     * Method for work with command in a different directory of current
     *
     * @param string $command
     * @param string $directory
     * @return ProcessResult
     */
    public function workInOtherDirectory(string $command, ?string $directory = __DIR__): ProcessResult
    {
        return Process::path($directory)->run($command);
    }

    /**
     * This method provides a way to run background processes, a method that is very
     * useful to leave processes running while your application continues to work without
     * any problem
     *
     * @param string $command
     * @return ProcessResult
     */
    public function asynchronous(string $command): ProcessResult
    {
        return Process::start($command);
    }
}
