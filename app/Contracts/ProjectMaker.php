<?php

namespace App\Contracts;

interface ProjectMaker
{
    /**
     * Method to execute for the implementation class
     *
     * @param array $librariesToInstall
     * @param string $projectLocation
     */
    public function execute(array $librariesToInstall, string $projectLocation): void;
}
