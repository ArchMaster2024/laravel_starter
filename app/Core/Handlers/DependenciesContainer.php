<?php

namespace App\Core\Handlers;

class DependenciesContainer
{
    public function __construct(
        private readonly array $pendingDependencies,
        private array $selectedDependencies = []
    ) {}

    public function __invoke()
    {


        $this->handle($this->pendingDependencies);

        return $this->selectedDependencies;
    }

    private function handle($dependencies)
    {
        if (array_key_exists('commands', $this->pendingDependencies)) {
            $this->addDependencies($this->pendingDependencies['commands']);
        }

        if (array_key_exists('type', $dependencies)) {
            $this->handleInput($dependencies);
        }


        foreach ($this->pendingDependencies as $key => $container) {
            if(!is_int($key)) continue;
            $this->createNewContainer($container);
        }
    }

    private function createNewContainer(array | null $dependencies): void
    {
        if(empty($dependencies)) return;
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

    private function handleInput(array $dependencies)
    {
        $input = new Input();
        $type = $dependencies['type'];

        if($type === 'choice' || $type === 'select') {
            $result = $input->$type($dependencies['label'], $dependencies['options']);
            $this->createNewContainer($result->option);
            return;
        }

        $isConfirmed = $input->confirm($dependencies['label']);
        if($isConfirmed){
            $this->createNewContainer($dependencies['options']['yes']);
        } else {
            $this->createNewContainer($dependencies['options']['no']);
        }
    }
}
