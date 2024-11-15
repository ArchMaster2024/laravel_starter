<?php

namespace App\Services\Linux;

use App\Contracts\ProcessesManager;
use Illuminate\Contracts\Process\ProcessResult;
use Illuminate\Support\Facades\Process;

class ProcessManager implements ProcessesManager
{
    public function synchronous(string $process): ProcessResult
    {
        return Process::run($process);
    }

    public function asynchronous(string $process): ProcessResult
    {
        return Process::start($process);
    }
}
