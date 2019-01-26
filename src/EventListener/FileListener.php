<?php


namespace SH\Scan\EventListener;

use SH\Scan\Event\EventInterface;

class FileListener
{
    private $handlers = [];
    
    public function addHandler($handler)
    {
        $this->handlers[] = $handler;
        
    }

    /**
     * @param EventInterface $event
     */
    public function onCheckFile(EventInterface $event)
    {
        $targetFile = $event->getTarget();
        foreach($this->handlers as $handler) {
            if (!$handler->execute($targetFile)) {
               $event->setInclusion(false);
               break;
            }
            
        }
    }
}

