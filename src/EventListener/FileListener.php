<?php


namespace SH\Scan\EventListener;

use SH\Scan\Event\EventInterface;

class FileListener
{
    private $rootDirectoryTime;
    private $pastLimitTime;
    private $futureLimitTime;
    private $inclusion = true;
    private $specificFile;
    
    /**
     * UpdatedCheckerListener constructor.
     *
     * @param \DateTimeImmutable $date
     * @param \DateInterval      $range
     * @param null               $specificFile
     */
    public function __construct(\DateTimeImmutable $date, \DateInterval $range, $specificFile = null)
    {
        $this->rootDirectoryTime = $date;
        $this->pastLimitTime     = $this->rootDirectoryTime->sub($range);
        $this->futureLimitTime   = $this->rootDirectoryTime->add($range);
        $this->specificFile      = $specificFile;
    }
    public function onFindExceptionUpdatedFile(EventInterface $event)
    {
        $targetFile = $event->getTarget();
//        $ext        = pathinfo($targetFile, PATHINFO_EXTENSION);
//        if ($ext !== 'php') {
//            $event->setInclusion(false);
//
//            return;
//        }
        if ($this->specificFile) {
            if (!in_array($targetFile, $this->specificFile)) {
                $event->setInclusion(false);

                return;
            }
        }
        try {
            $updatedTime = new \DateTimeImmutable('@'.filemtime($targetFile), new \DateTimeZone('Asia/Tokyo'));
        } catch (\Exception $e) {
            echo $e->getMessage();

        }
        $this->inclusion = ( $updatedTime < $this->pastLimitTime || $this->futureLimitTime < $updatedTime ) ? true : false;
        $event->setInclusion($this->inclusion);
    }
}

