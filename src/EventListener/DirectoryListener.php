<?php


namespace SH\Scan\EventListener;

use Symfony\Component\EventDispatcher\Event;

class DirectoryListener
{
    private $directories = [];

    public function addDirectories(array $directory)
    {
        $this->directories = $directory;
    }

    public function onExcludeDirectory(Event $event)
    {
        $targetDirectory = $event->getDirectory();
        foreach ($this->directories as $directory) {
            if ($directory === $targetDirectory) {
                $event->setInclusion(false);
                break;
            }
        }
    }
}