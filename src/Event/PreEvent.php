<?php


namespace SH\Scan\Event;

use Symfony\Component\EventDispatcher\Event;

class PreEvent extends Event implements EventInterface

{
    const NAME = 'app.pre';
    
    private $file;
    private $inclusion = true;

    /**
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->file;
    }
    
    function setInclusion($value)
    {
        $this->inclusion = $value;
    }
    
    public function isInclusion()
    {
        return $this->inclusion;
    }
}

