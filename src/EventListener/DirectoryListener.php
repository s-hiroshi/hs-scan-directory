<?php


namespace SH\Scan\EventListener;

use SH\Scan\Event\EventInterface;

class DirectoryListener
{
    private $directories = [];

    public function addDirectories(array $directory)
    {
        $this->directories = $directory;
    }

    public function onExcludeDirectory(EventInterface $event)
    {
        $targetDirectory = $event->getTarget();
        foreach ($this->directories as $directory) {
            if ($directory === $targetDirectory) {
                $event->setInclusion(false);
                break;
            }
        }
    }
}