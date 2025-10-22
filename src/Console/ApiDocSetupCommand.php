<?php

namespace Bone\OpenApi\Console;

use Bone\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ApiDocSetupCommand extends Command
{
    public function __construct(
        private array $packages
    ) {
        parent::__construct('docs:setup');
    }

    protected function configure(): void
    {
        $this->setDescription('Sets up a node project for Typespec API definitions');
        $this->setHelp('Sets up a node project for Typespec API definitions');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $directories = [];

        foreach ($this->packages as $package) {
            $output->writeln('Checking ' . $package . '..');


        }
    }
}
