<?php


namespace SH\Scan\Event;


interface EventInterface
{

    public function getTarget();
    
    public function setInclusion($value);
}