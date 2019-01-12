<?php


namespace HS\Scan\Services;


class UpdatedFileScan extends AbstractScan
{
    public function execute(string $directory) : array
    {
        $files = $this->getFiles($directory);
        $updatedFiles = [];
        foreach($files as $file) {
            $updatedFiles[$file] = date("Y/m/d H:i:s", filemtime($file)); 
        }
        return $updatedFiles;
    }
}