<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
$fileScan        = new \SH\Scan\Services\FileScan($dispatcher);

$application = new Application('scan-directory', '2.0.1');
$application->add(new \SH\Scan\Command\FileScanCommand($fileScan, $dispatcher));
try {
    $application->run();
} catch (\Exception $e) {
    echo $e->getMessage();
}
