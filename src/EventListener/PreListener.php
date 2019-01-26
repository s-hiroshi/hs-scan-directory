<?php


namespace SH\Scan\EventListener;

use SH\Scan\Event\EventInterface;

class PreListener
{
    /**
     * @param EventInterface $event
     */
    public function onCheckFile(EventInterface $event)
    {
        // do nothing.
    }
}

