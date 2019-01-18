<?php

namespace HS\Scan\Command;

use HS\Scan\Services\AbstractScan;
use HS\Scan\Event\ExclusionEvent;
use HS\Scan\EventListener\ExclusionListener;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Yaml\Yaml;

class FileScanCommand extends Command
{
    const COMMAND_NAME = 'hs:scan:list';

    private $scan;
    private $dispatcher;

    public function __construct(AbstractScan $scan, EventDispatcher $dispatcher)
    {
        $this->scan = $scan;
        $this->dispatcher = $dispatcher;
        parent::__construct(self::COMMAND_NAME);
    }

    protected function configure()
    {
        $this
            ->setName('hs:scan:list')
            ->setDescription('Display files list in directory(Including subdirectories)')
            ->addArgument('directory', InputArgument::REQUIRED, 'Relative directory path from console.php', null)
            ->addOption('exclusion', 'e', InputOption::VALUE_OPTIONAL, 'Yaml file that is Relative path of excluded directories path from console.php', null);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $exclusionDirectories = $input->getOption('exclusion');
        if ($exclusionDirectories) {
            $exclusionDirectories = Yaml::parseFile($exclusionDirectories);
            $listener             = new ExclusionListener();
            $listener->addDirectories($exclusionDirectories);
            $this->dispatcher->addListener(ExclusionEvent::NAME, [$listener, 'onExcludeAction']);
        }
        $out = $this->scan->execute($input->getArgument('directory'));
        $output->writeln($out);
    }
}
