<?php

namespace HS\Scan\Command;

use HS\Scan\Services\AbstractScan;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FileCountScanCommand extends Command
{
    const COMMAND_NAME = 'hs:scan:count';

    private $scan;

    public function __construct(AbstractScan $scan)
    {
        $this->scan = $scan;
        parent::__construct(self::COMMAND_NAME);
    }


    protected function configure()
    {
        $this
            ->setName('hs:scan:count')
            ->setDescription('Display number of files in directory(Including subdirectories)')
            ->addArgument('directory', InputArgument::REQUIRED, 'Relative directory path from console.php', null);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $out = $this->scan->execute($input->getArgument('directory'));
        $output->writeln($out);
    }
}
