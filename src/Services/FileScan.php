<?php


namespace SH\Scan\Services;


class FileScan
{
    private $files;

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
                $this->files[] = $path;
                continue;
            }
            $this->scan($path);
        }
    }

    /**
     * @param string $directory
     * @return string[]
     */
    public function getFiles(string $directory) : array
    {
        $this->scan($directory);
        return $this->files;
    }

}