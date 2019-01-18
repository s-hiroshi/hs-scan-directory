<?php


namespace HS\Scan\Services;


class FileScan extends AbstractScan
{
    /**
     * @param string $directory
     * @return string[]
     */
    public function execute(string $directory = null): array
    {
        return $this->getFiles($directory);
    }
}