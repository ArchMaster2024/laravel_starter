<?php

namespace App\Services;

use App\Contracts\ProjectMaker;

class ProjectMakerContext
{
    public function __construct(private ProjectMaker $projectMaker)
    {
    }

    public function setProjectMakerStrategy(ProjectMaker $projectMaker): void
    {
        $this->projectMaker = $projectMaker;
    }

    public function runProjectMakerBusinessLogic(string $instalationType, string $projectLocation): void
    {
        $librariesToInstall = [];
        switch ($instalationType) {
            case 'API':
                break;
            case 'Monolit':
                $librariesToInstall = $this->librariesToMonolit();
                break;
        }
        $this->projectMaker->execute($librariesToInstall, $projectLocation);
    }

    /**
     * Method for creating an array with differents packages and their configs to install
     *
     * @return array
     */
    private function librariesToMonolit(): array
    {
        return [
            [
                'type' => 'select',
                'label' => 'Select your starter kits',
                'options' => [
                    'Breeze' => [
                        'steps' => 4,
                        'commands' => [
                            'composer require laravel/breeze --dev',
                            'php artisan breeze:install',
                            'php artisan migrate',
                            'npm install',
                        ],
                    ],
                    'Jetstream' => [
                        'steps' => 2,
                        'commands' => [
                            'composer require laravel/jetstream',
                            [
                                'label' => 'What frontend framework do you prefer?',
                                'Livewire' => [
                                    'label' => 'What version do you prefer?',
                                    'Alone' => 'php artisan jetstream:install livewire',
                                    'Teams' => 'php artisan jetstream:install livewire --teams',
                                ],
                                'Inertia' => [
                                    'label' => 'What version do you prefer?',
                                    'Alone' => 'php artisan jetstream:install inertia',
                                    'Teams' => 'php artisan jetstream:install inertia --teams',
                                    'SSR' => 'php artisan jetstream:install inertia --ssr',
                                ],
                            ],
                        ],
                    ],
                    'Filament' => [
                        'steps' => 3,
                        'commands' => [
                            'composer require livewire/livewire',
                            'composer require filament/filament:"^3.2" -W',
                            'php artisan filament:install --panels',
                        ],
                    ],
                    'Custom' => true,
                ],
            ],
            [
                'type' => 'one',
                'label' => 'Do you like to install Pest for replace to PHPUnit?',
                'commands' => [
                    'composer remove phpunit/phpunit --dev',
                    'composer require pestphp/pest --dev --with-all-dependencies',
                    './vendor/bin/pest --init',
                    './vendor/bin/pest',
                ],
            ],
            [
                'type' => 'select',
                'label' => 'Select your frontend framework',
                'options' => [
                    'Livewire' => [
                        'steps' => 1,
                        'commands' => 'composer require livewire/livewire',
                    ],
                    'Inertia' => [
                        'steps' => 3,
                        'commands' => [
                            'composer require inertiajs/inertia-laravel',
                            'php artisan inertia:middleware',
                            'npm install @inertiajs/vue3@next',
                        ],
                    ],
                    'None' => [],
                ],
            ],
            [
                'type' => 'one',
                'label' => 'Do you like to use Telescope for debug your application?',
                'commands' => [
                    'composer require laravel/telescope --dev',
                    'php artisan telescope:install',
                    'php artisan migrate',
                ],
            ],
            [
                'type' => 'one',
                'label' => 'Do you like to use permission (Laravel permission by spatie) for manage your roles in laravel',
                'commands' => [
                    'composer require spatie/laravel-permission',
                    'php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"',
                    'php artisan optimize:clear',
                    'php artisan migrate',
                ],
            ]
        ];
    }
}
