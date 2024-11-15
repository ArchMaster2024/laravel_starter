<?php

namespace App\Commands;

use App\Contracts\ProcessesManager;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

// use Illuminate\Support\Facades\Process;

// use function Laravel\Prompts\text;

use function Laravel\Prompts\note;

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

    /**
     * Execute the console command.
     */
    public function handle(ProcessesManager $process): void
    {
        $echoTest = $process->synchronous('echo Hello World');
        note($echoTest->output());
        /* $projectName = text(label: 'What is your project name?', hint: 'Write \'.\' for to create the project in the current directory');
        $this->task('Installing Laravel', function () use ($projectName) {
            if ($projectName == '.') {
                $installer = Process::run('composer create-project laravel/laravel');
            }
            $installer = Process::run('composer create-project laravel/laravel ' . $projectName);
        }); */
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
