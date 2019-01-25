<?php


namespace SH\Scan\Event;

use Symfony\Component\EventDispatcher\Event;

class DirectoryEvent extends Event implements EventInterface
{
    const NAME = 'app.directory';

    private $directory;
    private $inclusion = true;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    public function getTarget()
    {
        return $this->directory;
    }

    function setInclusion(bool $value)
    {
        $this->inclusion = $value;
    }

    public function isInclusion() {

        return  $this->inclusion;
    }
}
