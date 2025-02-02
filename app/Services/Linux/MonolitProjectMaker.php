<?php

namespace App\Services\Linux;

use App\Contracts\ProjectMaker;
use App\Utils\MenuBuilder;
use Illuminate\Support\Arr;
use App\Utils\optionSelectedBuilder;
use function Laravel\Prompts\select;

class MonolitProjectMaker implements ProjectMaker
{
    private array $dependencyMethods = [
        'one' => 'processOneTypeDependency',
        'select' => 'processSelectTypeDependency',
    ];

    public function __construct(private readonly TaskManager $taskManager) {}

    public function execute(array $dependencies, string $projectName): void
    {
        foreach($dependencies as $dependency) {
            $method = $this->dependencyMethods[$dependency['type']] ?? null;
            if ($method != null) {
                $this->$method($dependency, $projectName);
            }
        }
    }

    /**
     * Method for manage the processing of dependecy array of type select
     *
     * @param array $dependencyConfig
     * @return bool
     */
    private function processSelectTypeDependency(array $dependencyConfig, string $location): void
    {
        $libraries = $dependencyConfig;
        $libraryToInstall = 0;
        $counter = 0;
        $optionSelected = null;
        do {
            $label = Arr::has($libraries, ['label']) ? $libraries['label'] : null;
            if (Arr::has($libraries, ['options'])) {
                $optionSelected = MenuBuilder::createMenu($libraries['options'], $label);
                if (is_array($libraries['options'][$optionSelected])) {
                    $counter = $libraries['options'][$optionSelected]['steps'];
                    $libraries = $libraries['options'][$optionSelected];
                }
            }
            if (Arr::has($libraries, ['commands'])) {
                if (is_array($libraries['commands'])) {
                    if (is_string($libraries['commands'][$libraryToInstall])) {
                        $this->taskManager->taskInSelectedLocation($libraries['commands'][$libraryToInstall], $location);
                        $counter--;
                        $libraryToInstall++;
                    }
                    if (Arr::exists($libraries['commands'], $libraryToInstall)){
                        if (is_array($libraries['commands'][$libraryToInstall])) {
                            $label = Arr::has($libraries['commands'][$libraryToInstall], ['label']) ? $libraries['commands'][$libraryToInstall]['label'] : null;
                            $optionSelected = MenuBuilder::createMenu($libraries['commands'][$libraryToInstall], $label, ['label']);
                            $label = Arr::has($libraries['commands'][$libraryToInstall][$optionSelected], ['label']) ? $libraries['commands'][$libraryToInstall][$optionSelected]['label'] : null;
                            $optionSelected2 = MenuBuilder::createMenu($libraries['commands'][$libraryToInstall][$optionSelected], $label, ['label']);
                            $this->taskManager->taskInSelectedLocation($libraries['commands'][$libraryToInstall][$optionSelected][$optionSelected2], $location);
                            $counter--;
                            $libraryToInstall++;
                        }
                    }
                } else {
                    $this->taskManager->taskInSelectedLocation($libraries['commands'], $location);
                    $counter = 0;
                }
            }
        } while ($counter > 0);
        /* $dependenciesToInstall = MenuBuilder::createMenu($dependencyConfig['options'], $dependencyConfig['label']);
        foreach($dependencyConfig['options'] as $key => $dependencies) {
            if ($key == $dependenciesToInstall && $dependenciesToInstall !== 'None') {
                if (empty($dependencies) || $dependencies !== true) {
                    if ($dependencies['steps'] == 1) {
                        $this->taskManager->taskInSelectedLocation($dependencies['commands'], $location);
                    }
                    for($i = 0; $i < $dependencies['steps']; $i++) {
                        if (gettype($dependencies['commands'][$i]) == "array") {
                            $firstLevelOptions = MenuBuilder::createMenu($dependencies['commands'][$i], $dependencies['commands'][$i]['label'], ['label']);
                            foreach ($dependencies['commands'][$i][$firstLevelOptions] as $key => $value) {
                                $secondLevelOptions = MenuBuilder::createMenu($dependencies['commands'][$i][$firstLevelOptions], $dependencies['commands'][$i][$firstLevelOptions]['label'], ['label']);
                                $this->taskManager->taskInSelectedLocation($dependencies['commands'][$i][$firstLevelOptions][$secondLevelOptions], $location);
                                break;
                            }
                        } else {
                            $this->taskManager->taskInSelectedLocation($dependencies['commands'][$i], $location);
                        }
                    }
                }
            }
        } */
    }

    /**
     * Method for manage the processing of dependecy array of type one
     *
     * @param array $dependencyConfig
     * @param string $location
     */
    private function processOneTypeDependency(array $dependencyConfig, string $location): void
    {
        $dependencyToInstall = select(
            label: $dependencyConfig['label'],
            options: ['Yes', 'No'],
        );
        if ($dependencyToInstall == 'Yes') {
            foreach ($dependencyConfig['commands'] as $key => $value) {
                $this->taskManager->taskInSelectedLocation($value, $location);
            }
        }
    }
}
