<?php


namespace SH\Scan\EventListener\Handler;

class TimeCheckHandler
{
    private $rootDirectoryTime;
    private $pastLimitTime;
    private $futureLimitTime;

    /**
     * @param \DateTimeImmutable $date
     * @param \DateInterval      $range
     */
    public function __construct(\DateTimeImmutable $date, \DateInterval $range)
    {
        $this->rootDirectoryTime = $date;
        $this->pastLimitTime     = $this->rootDirectoryTime->sub($range);
        $this->futureLimitTime   = $this->rootDirectoryTime->add($range);
    }

    /**
     * @param $targetFile
     * @return bool
     */
    public function execute($targetFile)
    {
        try {
            $updatedTime = new \DateTimeImmutable('@'.filemtime($targetFile), new \DateTimeZone('Asia/Tokyo'));
        } catch (\Exception $e) {
            echo $e->getMessage();

        }
        return ( $updatedTime < $this->pastLimitTime || $this->futureLimitTime < $updatedTime ) ? true : false;
    }
}