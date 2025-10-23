<?php

namespace Bone\OpenApi\Console;

use Bone\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateVendorsCommand extends AbstractVendorUpdateCommand
{
    protected function configure(): void
    {
        $this->setDescription('Updates vendor API documentation');
        $this->setHelp('Regenerate the vendors.tsp Typespec files for vendor packages');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = $this->getIO($input, $output);
        $io->title('☠️  Update vendor API definitions');
        $this->importPackageDefinitions($io);

        return Command::SUCCESS;
    }
}
