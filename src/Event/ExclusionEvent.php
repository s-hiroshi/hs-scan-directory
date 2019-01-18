<?php


namespace HS\Scan\Event;


use Symfony\Component\EventDispatcher\Event;

class ExclusionEvent extends Event
{
    const NAME = 'directory.exclusion';

    private $directory;
    private $isExclusion = false;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    public function getDirectory()
    {
        return $this->directory;
    }

    function setIsExclusion(bool $value)
    {
        $this->isExclusion = $value;
    }
    
    public function isExcluded() {
    
    return  $this->isExclusion;
    }
}