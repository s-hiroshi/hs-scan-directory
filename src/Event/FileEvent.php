<?php


namespace SH\Scan\Event;

use Symfony\Component\EventDispatcher\Event;

class FileEvent extends Event implements EventInterface

{
    const NAME = 'app.file';
    private $file;
    private $inclusion = true;
    public function __construct($file)
    {
        $this->file = $file;
    }
    public function getTarget()
    {
        return $this->file;
    }
    function setInclusion(bool $value)
    {
        $this->inclusion = $value;
    }
    public function isInclusion()
    {
        return $this->inclusion;
    }
}

