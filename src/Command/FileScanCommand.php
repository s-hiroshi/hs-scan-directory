<?php

namespace SH\Scan\Command;

use SH\Scan\Event\DirectoryEvent;
use SH\Scan\Event\FileEvent;
use SH\Scan\EventListener\DirectoryListener;
use SH\Scan\EventListener\FileListener;
use SH\Scan\EventListener\Handler\CheckSpecifFileHandler;
use SH\Scan\EventListener\Handler\TimeCheckHandler;
use SH\Scan\Services\FileScan;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Yaml\Yaml;

class FileScanCommand extends Command
{
    const COMMAND_NAME = 'hs:scan';

    private $fileScan;
    private $dispatcher;

    /**
     * @param FileScan        $fileScan
     * @param EventDispatcher $dispatcher
     */
    public function __construct(FileScan $fileScan, EventDispatcher $dispatcher)
    {
        $this->fileScan = $fileScan;
        $this->dispatcher = $dispatcher;
        parent::__construct(self::COMMAND_NAME);
    }

    protected function configure()
    {
        $this
            ->setName('scan')
            ->setDescription('サブディレクトリも含めてディレクトリのファイルを出力')
            ->addArgument('directory', InputArgument::REQUIRED, '走査ディレクトリパス（console.phpからの相対パス）', null)
            ->addOption(
                'interval',
                'i',
                InputOption::VALUE_OPTIONAL,
                '走査ディレクトリの更新日から指定した間隔を超える更新日を持つファイルを出力（間隔は間隔指示子で指定：例 2時間 PT2H）',
                null
            )
            ->addOption(
                'exclusion',
                'e',
                InputOption::VALUE_OPTIONAL,
                '指定したディレクトリを除外（除外するディレクトリのパスを記載したYamlファイルを指定 console.phpからの相対パス）',
                null
            )
            ->addOption(
                'files',
                'f',
                InputOption::VALUE_OPTIONAL,
                '指定したファイルだけを走査（ファイルパスを記載したYamlファイルを指定 console.phpからの相対パス）',
                null
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileListener = new FileListener();
        $specificFiles = $input->getOption('files');
        if ($specificFiles) {
            $specificFiles = Yaml::parseFile($specificFiles);
            $fileListener->addHandler(new CheckSpecifFileHandler($specificFiles));
        }
        $interval = $input->getOption('interval');
        if ($interval) {
            $rootDirectory = $input->getArgument('directory');
            $rootDirectoryTime = new \DateTimeImmutable('@' . filectime($rootDirectory));
            $rootDirectoryTime->setTimezone(new \DateTimeZone('Asia/Tokyo'));
            $fileListener->addHandler(new TimeCheckHandler($rootDirectoryTime,new \DateInterval($interval)));
        }

        $this->dispatcher->addListener(FileEvent::NAME, [$fileListener, 'onCheckFile']);
        
        $exclusionDirectories = $input->getOption('exclusion');
        if ($exclusionDirectories) {
            $exclusionDirectories = Yaml::parseFile($exclusionDirectories);
            $listener             = new DirectoryListener();
            $listener->addDirectories($exclusionDirectories);
            $this->dispatcher->addListener(DirectoryEvent::NAME, [$listener, 'onExcludeDirectory']);
        }
        $out = $this->fileScan->getFiles($input->getArgument('directory'));
        $output->writeln($out);
    }
}
