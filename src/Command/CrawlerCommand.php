<?php

namespace App\Command;

use App\Service\NewsGrabber;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:crawler',
    description: 'Import news from internet',
)]
class CrawlerCommand extends Command
{
    public function __construct(readonly private NewsGrabber $newsGrabber)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('count', InputArgument::OPTIONAL, 'Count of news to import')
            ->addOption('dryRun', null, InputOption::VALUE_OPTIONAL, 'Dry run')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = $input->getArgument('count');
        $dryRun = $input->getOption('dryRun');

        $logger = new ConsoleLogger($output);
        $this->newsGrabber->setLogger($logger)->importNews($count, $dryRun);

        return Command::SUCCESS;
    }
}
