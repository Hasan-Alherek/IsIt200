<?php

namespace App\Command;

use App\Service\WebsiteStatusChecker;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:check-website-status',
    description: 'Add a short description for your command',
)]
class CheckWebsiteStatusCommand extends Command
{
    public function __construct(
        private WebsiteStatusChecker $websiteStatusChecker
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try
        {
            if(!$this->websiteStatusChecker->check())
                throw new \Exception('Website status check failed');
        }
        catch (\Exception $e)
        {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }
        return Command::SUCCESS;
    }
}
