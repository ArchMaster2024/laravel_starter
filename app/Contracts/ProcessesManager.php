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
    /**
     * Method for interact with operative system
     *
     * @param string $command
     */
    public function interactWithSystem(string $command): ProcessResult;
    /**
     * Method for change the working directory
     *
     * @param string $command
     * @param string $directory
     */
    public function workInOtherDirectory(string $command, ?string $directory = __DIR__): ProcessResult;
}
