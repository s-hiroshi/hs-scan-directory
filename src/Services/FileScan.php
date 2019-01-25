<?php


namespace SH\Scan\Services;


use SH\Scan\Event\DirectoryEvent;
use SH\Scan\Event\FileEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @property array list
 */
class FileScan
{
    private $files = [];
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
            if (is_link($path)) {
                continue;
            }
            if (is_file($path)) {
                $fileEvent = new FileEvent($path);
                $this->dispatcher->dispatch(FileEvent::NAME, $fileEvent);
                if ($fileEvent->isInclusion()) {
                    $this->files[] = $path;
                }
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