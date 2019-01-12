<?php


namespace HS\Scan\Services;


abstract class AbstractScan
{
    protected $list;

    /**
     * @param string $directory
     */
    protected function scan(string $directory): void
    {
        $files = scandir($directory);
        $files = array_filter(
            $files,
            function ($file) {
                return !in_array($file, ['.', '..']);
            }
        );
        foreach ($files as $file) {
            $path = rtrim($directory, '/').'/'.$file;
            if (is_file($path)) {
                $this->list[] = $path;
                continue;
            }
            $this->scan($path);
        }
    }

    /**
     * @param $directory
     * @return []
     */
    protected function getFiles(string $directory) : array
    {
        $this->scan($directory);
        return $this->list;
    }

    /**
     * @param string $directory
     * @return []|int
     */
    abstract public function execute(string $directory);
}