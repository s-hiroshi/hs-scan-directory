<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;

$fileScan        = new \HS\Scan\Services\FileScan();
$fileCountScan   = new \HS\Scan\Services\FileCountScan();
$updatedFileScan = new \HS\Scan\Services\UpdatedFileScan();

$application = new Application('hs-scan', '0.0.1');
$application->add(new \HS\Scan\Command\FileScanCommand($fileScan));
$application->add(new \HS\Scan\Command\FileCountScanCommand($fileCountScan));
$application->add(new \HS\Scan\Command\UpdatedFileScanCommand($updatedFileScan));
try {
    $application->run();
} catch (\Exception $e) {
    echo $e->getMessage();
}
