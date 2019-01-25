<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;

$fileScan        = new \SH\Scan\Services\FileScan();

$application = new Application('scan-directory', '0.0.1');
$application->add(new \SH\Scan\Command\FileScanCommand($fileScan));
try {
    $application->run();
} catch (\Exception $e) {
    echo $e->getMessage();
}
