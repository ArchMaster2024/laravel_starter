<?php

namespace App\Commands;

// use App\Contracts\ProcessesManager;
// use App\Services\Linux\TaskManager;
use App\Services\LaravelInstaller;
// use App\Contracts\ProjectMaker;
use App\Services\ProjectMakerContext;
use App\Services\Linux\MonolitProjectMaker;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\text;
use function Laravel\Prompts\note;
use function Laravel\Prompts\info;
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

    public function __construct(private readonly LaravelInstaller $laravelInstaller, private readonly MonolitProjectMaker $monolitProject, private ProjectMakerContext $projectContext)
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
        $this->laravelInstaller->install($projectLocation, $this);
        info("By default Laravel come with PHPUnit test library");
        $scaffolding = select(label: 'Directory scaffolding', options: ['API', 'Monolit']);
        switch ($scaffolding) {
            case 'API':
                // TODO: Write API scaffolding flow
                note('API');
                break;
            case 'Monolit':
                // TODO: Write Monolit scaffolding flow
                $this->projectContext->setProjectMakerStrategy($this->monolitProject);
                $this->projectContext->runProjectMakerBusinessLogic('Monolit', $projectLocation);
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
