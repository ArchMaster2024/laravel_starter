<?php

namespace App\Services\Linux;

use App\Contracts\ProjectMaker;
use Illuminate\Support\Arr;
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
            if ($method) {
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
        $optionsToSelect = Arr::map($dependencyConfig['options'], function ($value, string $key) {
            return $key;
        });
        $dependenciesToInstall = select(
            label: $dependencyConfig['label'],
            options: $optionsToSelect,
        );
        foreach($dependencyConfig['options'] as $key => $dependencies) {
            if ($key == $dependenciesToInstall && $dependenciesToInstall !== 'None') {
                if (empty($dependencies) || $dependencies !== true) {
                    if ($dependencies['steps'] == 1) {
                        $this->taskManager->taskInSelectedLocation($dependencies['commands'], $location);
                    }
                    for($i = 0; $i < $dependencies['steps']; $i++) {
                        if (gettype($dependencies['commands'][$i]) == "array") {
                            $firstLevelOptions = Arr::map($dependencies['commands'][$i], function ($value, string $key) {
                                if (is_array($value)) {
                                    return $key;
                                }
                            });
                            $firstLevelOptionsExcludeNull = Arr::except($firstLevelOptions, ['label']);
                            $firstLevelOptionToInstall = select(
                                label: $dependencies['commands'][$i]['label'],
                                options: $firstLevelOptionsExcludeNull,
                            );
                            foreach ($dependencies['commands'][$i][$firstLevelOptionToInstall] as $key => $value) {
                                $arrayToManipulate = $dependencies['commands'][$i][$firstLevelOptionToInstall];
                                $secondLevelOptions = Arr::map($arrayToManipulate, function ($value, string $key) {
                                    if ($key !== 'label') {
                                        return $key;
                                    }
                                });
                                $secondLevelOptionsExcludeLabel = Arr::except($secondLevelOptions, ['label']);
                                $secondLevelOptionToInstall = select(
                                    label: $arrayToManipulate['label'],
                                    options: $secondLevelOptionsExcludeLabel,
                                );
                                $this->taskManager->taskInSelectedLocation($arrayToManipulate[$secondLevelOptionToInstall], $location);
                                break;
                            }
                        } else {
                            $this->taskManager->taskInSelectedLocation($dependencies['commands'][$i], $location);
                        }
                    }
                }
            }
        }
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
