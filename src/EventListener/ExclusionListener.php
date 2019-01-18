<?php


namespace HS\Scan\EventListener;

use Symfony\Component\EventDispatcher\Event;

class ExclusionListener
{
    private $directories = [];

    public function addDirectories(array $directory)
    {
        $this->directories = $directory;
    }

    public function onExcludeAction(Event $event)
    {
        $targetDirectory = $event->getDirectory();
        foreach ($this->directories as $directory) {
            if ($directory === $targetDirectory) {
                $event->setIsExclusion(true);
                break;
            }
        }
    }
}