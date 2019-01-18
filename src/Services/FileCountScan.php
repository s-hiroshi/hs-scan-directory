<?php


namespace HS\Scan\Services;

class FileCountScan extends AbstractScan
{

    public function execute(string $directory) : int
    {
        return count($this->getFiles($directory));
    }
}