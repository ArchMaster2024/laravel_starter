<?php

namespace App\Core\Services;

use App\Core\Handlers\Result;
use Illuminate\Contracts\Process\ProcessResult;
use Illuminate\Process\Exceptions\ProcessTimedOutException;

use function Laravel\Prompts\select;
use function Laravel\Prompts\info;
use function Laravel\Prompts\text;
use function Laravel\Prompts\spin;

use Illuminate\Support\Facades\Process;
use RuntimeException;

class LaravelInstaller {
    public function install() : Result
    {

        $version = select(label: 'Laravel version', options: [10, 11], required: true);
        $options = config("laravel_$version");

        if(empty($options)) {
            return Result::Failure('Version not configure');
        }

        $projectName = text(label : 'Project namme', placeholder: 'awesome_project', required : true);

        $data = (object) [
            'projectName' => $projectName,
            'options' => $options,
            'version' => $version
        ];

        $result = spin(function() use ($data): Result{
            try {
                Process::timeout(3000)->run("composer create-project laravel/laravel:^$data->version $data->projectName")->throw();
            } catch(ProcessTimedOutException | RuntimeException $error ){
                return Result::Failure($error->getMessage());
            }

            return Result::Success($data);
        }, 'Instaling Laravel');

        return $result;
    }
}
