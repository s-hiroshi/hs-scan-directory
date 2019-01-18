<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;

$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
$fileScan        = new \HS\Scan\Services\FileScan($dispatcher);
$fileCountScan   = new \HS\Scan\Services\FileCountScan($dispatcher);
$updatedFileScan = new \HS\Scan\Services\UpdatedFileScan($dispatcher);

$application = new Application('hs-scan', '0.0.1');
$application->add(new \HS\Scan\Command\FileScanCommand($fileScan, $dispatcher));
$application->add(new \HS\Scan\Command\FileCountScanCommand($fileCountScan));
$application->add(new \HS\Scan\Command\UpdatedFileScanCommand($updatedFileScan));
try {
    $application->run();
} catch (\Exception $e) {
    echo $e->getMessage();
}
