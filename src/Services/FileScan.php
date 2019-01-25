<?php


namespace SH\Scan\Services;


use SH\Scan\Event\DirectoryEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

class FileScan
{
    private $files;
    private $dispatcher;
    
    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param string $directory
     */
    protected function scan(string $directory): void
    {
        $files = scandir($directory);
        $files = array_filter(
            $files,
            function ($file) {
                return !in_array($file, ['.', '..']);
            }
        );
        foreach ($files as $file) {
            $path = rtrim($directory, '/').'/'.$file;
            if (is_file($path)) {
                $this->files[] = $path;
                continue;
            }
            $directoryEvent = new DirectoryEvent($path);
            $this->dispatcher->dispatch(DirectoryEvent::NAME, $directoryEvent);
            if (!$directoryEvent->isInclusion()) {
                continue;
            }
            $this->scan($path);
        }
    }

    /**
     * @param string $directory
     * @return string[]
     */
    public function getFiles(string $directory) : array
    {
        $this->scan($directory);
        return $this->files;
    }

}