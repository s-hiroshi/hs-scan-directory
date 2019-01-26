<?php


namespace SH\Scan\EventListener\Handler;


class CheckSpecifFileHandler
{
    private $specificFiles;

    /**
     * @param string[] $specificFiles
     */
    public function __construct($specificFiles)
    {
        $this->specificFiles = $specificFiles;
    }

    /**
     * @param string $targetFile
     * @return bool
     */
    public function execute($targetFile)
    {
        if ($this->specificFiles) {
            if (!in_array($targetFile, $this->specificFiles)) {

                return false;
            }
        }
        return true;
    }
}