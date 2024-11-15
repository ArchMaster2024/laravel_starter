<?php

namespace App\Contracts;

use Illuminate\Contracts\Process\ProcessResult;

/**
 * This interface work like a type for the different classes need it them
 */
interface ProcessesManager
{
    /**
     * Method for create an synchronous process in the system
     *
     * @param string $process
     */
    public function synchronous(string $process): ProcessResult;
    /**
     * Method for create an asynchronous process in the system
     *
     * @param string $process
     */
    public function asynchronous(string $process): ProcessResult;
}
