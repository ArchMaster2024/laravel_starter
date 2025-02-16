<?php

namespace App\Core\Services;

use App\Core\Handlers\DependenciesContainer;

class DependencyInstaller {
    private array $dependencies;
    private string | null $directory = null;
    public function __construc(

    ){}
    public function choose(array $dependencies){
        $container = new DependenciesContainer($dependencies);
        $this->dependencies = $container();
    }
    public function install(): void{
        print_r($this->dependencies);
    }

    public function onDirectory(string $directory): self
    {
        $this->directory = $directory;
        return $this;
    }



}

