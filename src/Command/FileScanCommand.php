<?php

namespace SH\Scan\Command;

use SH\Scan\Event\DirectoryEvent;
use SH\Scan\Event\FileEvent;
use SH\Scan\EventListener\DirectoryListener;
use SH\Scan\EventListener\FileListener;
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
            ->setDescription('サブディレクトリも含めてディレクトリを走査してファイルを出力')
            ->addArgument('directory', InputArgument::REQUIRED, '対象ディレクトリパス（console.phpからの相対パス）', null)
            ->addArgument('range', InputArgument::REQUIRED, '許容する誤差（間隔指示子 例：2時間 PT2H', null)
            ->addOption(
                'exclusion',
                'e',
                InputOption::VALUE_OPTIONAL,
                '除外するパスを記載したYamlファイルを指定（ファイル内に記載する除外ディレクトリはconsole.phpからの相対パス）',
                null
            )
            ->addOption('files', 'f', InputOption::VALUE_OPTIONAL, '特定のファイルのみを対象にする場合はパスを記載したYamlファイルを指定（ファイル内に記載する除外ディレクトリはconsole.phpからの相対パス）', null);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rootDirectory = $input->getArgument('directory');
        $rootDirectoryTime = new \DateTimeImmutable('@' . filectime($rootDirectory));
        $rootDirectoryTime->setTimezone(new \DateTimeZone('Asia/Tokyo'));
        $specificFile = $input->getOption('files');
        if ($specificFile) {
            $specificFile = Yaml::parseFile($specificFile);
        }
        $range = $input->getArgument('range');
        $interval = new \DateInterval($range);
        $fileListener = new FileListener($rootDirectoryTime, $interval, $specificFile);
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
