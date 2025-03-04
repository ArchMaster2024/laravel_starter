<?php

namespace App\Core\Handlers;

use Illuminate\Process\Exceptions\ProcessTimedOutException;
use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Process as ProcessFacade;
use RuntimeException;

class Process {
    private string | null $directory = null;

    public function setPath(string $path): self
    {
        $this->directory = $path;
        return $this;
    }

    public function execute(string $command): Result
    {
        try {
            $this->process()->run($command)->throw();
        } catch (ProcessTimedOutException | RuntimeException $error) {
            return Result::Failure( $error->getMessage() );
        }

        return Result::Success();
    }

    private function process(): PendingProcess
    {
        $process = ProcessFacade::timeout(3000);

        if( empty( $this->directory ) === false ) {
            $process = $process->path( $this->directory );
        }

        return $process;
    }
}
