<?php

namespace App\Core\Handlers;

class DependenciesContainer {
    public function __construct(
        private readonly array $pendingDependencies,
        private array $selectedDependencies = []
    ){}

    public function __invoke(){


        $this->handle($this->pendingDependencies);

        return $this->selectedDependencies;
    }

    private function handle($dependencies){
        if(array_key_exists('type', $dependencies)){
            if($dependencies['type'] === 'select'){
                $input = new Input();
                $result = $input->select($dependencies['label'], $dependencies['options']);
                $this->createNewContainer($result->option);
            }
            return;
        }


        if(array_key_exists('commands', $this->pendingDependencies)){
            $this->addDependencies($this->pendingDependencies['commands']);
            return;
        }

        foreach($this->pendingDependencies as $container){
            $this->createNewContainer($container);
        }

    }

    private function createNewContainer(array $dependencies): void
    {
        $dependenciesContainer = new DependenciesContainer($dependencies);

        $selectedDependencies = $dependenciesContainer();
        $this->addDependencies($selectedDependencies);
    }

    private function addDependencies(array $dependencies): void
    {
        $this->selectedDependencies = array_merge(
            $this->selectedDependencies,
            $dependencies
        );
    }
}
