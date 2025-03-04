<?php

return [
    // scaffolding
    [
        'type' => 'select',
        'label' => 'Select your scaffolding',
        'options' => [
            'API' => null,
            'Monolit' => [
                'type' => 'select',
                'label' => 'Select your starter kit',
                'options' => [
                    'Breeze' => [
                        'commands' => [
                            'composer require laravel/breeze --dev',
                            'php artisan breeze:install',
                            'php artisan migrate',
                            'npm install',
                        ],
                    ],
                    'Jetstream' => [
                        'commands' => [
                            'composer require laravel/jetstream'
                        ],
                        'type' => 'select',
                        'label' => 'What frontend framework do you prefer?',
                        'options' => [
                            'Livewire' => [
                                'type' => 'select',
                                'label' => 'What version do you prefer?',
                                'options' => [
                                    'Alone' => [
                                        'commands' => ['php artisan jetstream:install livewire']
                                    ],
                                    'Teams' => [
                                        'commands' => ['php artisan jetstream:install livewire --teams']
                                    ]
                                ]
                            ],
                            'Inertia' => [
                                'type' => 'select',
                                'label' => 'What version do you prefer?',
                                'options' => [
                                    'Alone' => [
                                        'commands' => ['php artisan jetstream:install inertia']
                                    ],
                                    'Teams' => [
                                        'commands' => ['php artisan jetstream:install inertia --teams']
                                    ],
                                    'SSR' => [
                                        'commands' => ['php artisan jetstream:install inertia --ssr']
                                    ],
                                ]
                            ],
                        ]
                    ],
                    'Filament' => [
                        'commands' => [
                            'composer require livewire/livewire',
                            'composer require filament/filament:"^3.2" -W',
                            'php artisan filament:install --panels',
                        ],
                    ],
                    'none' => null
                ]
            ]
        ]
    ],
    // Libraries
    [
        [
            'type' => 'choice',
            'label' => 'Which of these libraries would you like to install?',
            'options' => [
                'spatie permission' => [
                    'commands' => [
                        'composer require spatie/laravel-permission',
                        'php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"'
                    ]
                ],
                'spatie laravel-pdf' => [
                    'commands' => [
                        'composer require spatie/laravel-pdf'
                    ]
                ],
                'telescope' => [
                    'commands' => [
                        'composer require laravel/telescope --dev',
                        'php artisan telescope:install',
                        'php artisan migrate'
                    ]
                ],
                'laravel excel' => [
                    'commands' => [
                        'composer require "maatwebsite/excel:^3.1"',
                        'php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config'
                    ]
                ]
            ]
        ]
    ],
    // Testing
    [
        'type' => 'confirm',
        'label' => 'Would you like to use PEST as a testing framework for your application?',
        'options' => [
            'yes' =>  [
                'commands' =>  [
                    'composer remove --dev phpunit/phpunit',
                    'composer require pestphp/pest --dev --with-all-dependencies',
                    './vendor/bin/pest --init'
                ]
            ],
            'no' =>  null,
        ]
    ]
];
