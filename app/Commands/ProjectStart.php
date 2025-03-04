<?php

namespace App\Commands;

use App\Core\Services\DependencyInstaller;
use App\Core\Services\LaravelInstaller;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\error;

class ProjectStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-proyect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a new Laravel Project';

    public function __construct(
        private laravelInstaller $laravelInstaller,
        private DependencyInstaller $dependencies
    ){
        parent::__construct();
    }


    /**
     * Execute the console command.
     */
    public function handle()
    {
        info('Welcome to laravel starter');

        $this->task('Instaling Laravel', function () use(&$result): bool {
            $result = $this->laravelInstaller->install();
            return $result->isSuccess();
        });

        if($result->isFailure()){
            error('An error has occurred');
            error('Here is more information about the failure:');
            error($result->getContent());

            return self::FAILURE;
        }

        $data = $result->getContent();

        $dependencies = $data->options;
        $directory = $data->projectName;

        $this->task('Installing aditional libraries', function () use($dependencies, $directory, &$result) : bool {
            $this->dependencies->choose($dependencies);
            $result = $this->dependencies->onDirectory($directory)->install();
            return $result->isSuccess();
        });

        if($result->isFailure()){
            error('An error has occurred');
            error('Here is more information about the failure:');
            error($result->getContent());

            return self::FAILURE;
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