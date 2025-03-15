<?php

namespace Bone\OpenApi\Console;

use ReflectionClass;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DocGeneratorCommand extends Command
{
    public function __construct(
        private array $packages
    ) {
        parent::__construct('docs');
    }

    protected function configure(): void
    {
        $this->setName('generate');
        $this->setDescription('Generate Open API docs');
        $this->setHelp('Scans source code fow swagger php annotations');
        $this->addArgument('destination', InputArgument::OPTIONAL, 'path to save json', 'data/docs/api.json');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $directories = [];

        foreach ($this->packages as $package) {
            $output->writeln('Loading ' . $package . '..');

            if (class_exists($package)) {
                $mirror = new ReflectionClass($package);
                $location = $mirror->getFileName();
                $explosion = explode('/', $location);
                array_pop($explosion);
                $path = implode('/', $explosion);

                if (is_dir($path)) {
                    $directories[] = $path;
                }
            }
        }

        $openapi = \OpenApi\scan($directories);
        $destination = $input->getArgument('destination');
        $json = $openapi->toJson();
        file_put_contents($destination, $json);
        $output->writeln($destination . ' generated.');

        return Command::SUCCESS;
    }
}
