<?php

namespace HS\Scan\Command;

use HS\Scan\Services\AbstractScan;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdatedFileScanCommand extends Command
{
    const COMMAND_NAME = 'hs:scan:updated';

    private $scan;

    public function __construct(AbstractScan $scan)
    {
        $this->scan = $scan;
        parent::__construct(self::COMMAND_NAME);
    }

    protected function configure()
    {
        $this
            ->setName('hs:scan:updated')
            ->setDescription('Display update date time of files in directory')
            ->addArgument('directory', InputArgument::REQUIRED, 'Relative directory path from console.php(Including subdirectories)', null);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $out = $this->scan->execute($input->getArgument('directory'));
        $output->writeln($out);
    }
}
