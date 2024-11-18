<?php

namespace App\Commands;

use App\Contracts\ProcessesManager;
use App\Services\Linux\TaskManager;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\text;
use function Laravel\Prompts\note;
use function Laravel\Prompts\error;
use function Laravel\Prompts\select;

class ProjectStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-project';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a new Laravel Project';

    public function __construct(private ProcessesManager $process, private TaskManager $taskManager)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $projectLocation = text(label: 'Project name', placeholder: 'Awesome_project', hint: 'If you write \'.\' we create project in current directory');
        // NOTE: We start with the flow if the user write the name of a project
        if ($projectLocation == '.') {
            // TODO: Write project in actual directory
        }
        $installLaravel = $this->task('Installing Laravel', fn () => $this->taskManager->simpleSynchronousTask("composer create-project laravel/laravel $projectLocation"));
        if ($installLaravel != true) {
            error('An error occurred when trying to install the basic Laravel structure');
        }
        $scaffolding = select(label: 'Directory scaffolding', options: ['API', 'Monolit']);
        switch ($scaffolding) {
            case 'API':
                // TODO: Write API scaffolding flow
                note('API');
                break;
            case 'Monolit':
                $testingFramework = select(label: 'What testing framewrok do you prefer?', options: ['Unit', 'Pest']);
                if ($testingFramework == 'Pest') {
                    $removePHPUnit = $this->task('Removing PHPUnit', fn () => $this->taskManager->taskInSelectedLocation('composer remove phpunit/phpunit', $projectLocation));
                    if ($removePHPUnit != true) {
                        error('An error occurred when trying to remove PHPUnit library');
                    }
                    $installPest = $this->task('Installing Pest', function () use ($projectLocation) {
                        $pestInstalled = $this->taskManager->taskInSelectedLocation('composer require pestphp/pest --dev --with-all-dependencies', $projectLocation);
                        if ($pestInstalled != true) {
                            error('An error occurred when trying to install Pest PHP');
                            return false;
                        }
                        $pestInitialize = $this->taskManager->taskInSelectedLocation('./vendor/bin/pest --init', $projectLocation);
                        if ($pestInitialize != true) {
                            error('An error occurred when trying to initialize Pest PHP');
                            return false;
                        }
                        $pestTesting = $this->taskManager->taskInSelectedLocation('./vendor/bin/pest', $projectLocation);
                        if ($pestTesting != true) {
                            error('An error occured when trying to testing Pest PHP');
                            return false;
                        }
                        return true;
                    });
                }
                break;
            default:
                error('HaHa you try hack my app, but I\'m more smart than you and I get ahead of your response');
                break;
        }
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
