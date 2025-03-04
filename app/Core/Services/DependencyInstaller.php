<?php

namespace App\Core\Services;

use App\Core\Handlers\DependenciesContainer;
use App\Core\Handlers\Process;
use App\Core\Handlers\Result;
use function Laravel\Prompts\progress;


class DependencyInstaller {
    private array $dependencies;
    private string | null $directory = null;
    public function __construct(
        private Process $process
    ){}

    public function choose(array $dependencies){
        $container = new DependenciesContainer($dependencies);
        $this->dependencies = $container();
    }
    public function install(): Result
    {
        $progress = progress(
            label: 'Installing Dependencies',
            steps: count( $this->dependencies )
        );

        foreach($this->dependencies as $dependency){
            $result = $this->process->execute($dependency);
            if($result->isFailure()){
                $progress->finish();
                return $result;
            }
            $progress->advance();
        }
        $progress->finish();
        return Result::Success();
    }

    public function onDirectory(string $directory): self
    {
        $this->process->setPath($directory);
        return $this;
    }



}
