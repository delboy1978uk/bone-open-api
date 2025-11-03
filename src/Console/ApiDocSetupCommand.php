<?php

namespace Bone\OpenApi\Console;

use Bone\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use function dirname;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function is_dir;

class ApiDocSetupCommand extends AbstractVendorUpdateCommand
{
    protected function configure(): void
    {
        $this->setDescription('Sets up a node project for Typespec API definitions');
        $this->setHelp('Sets up a node project for Typespec API definitions');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getIO($input, $output);
        $io->title('☠️  Setup API definitions');
        $this->createSpecFolderAndFiles($io);
        $this->importPackageDefinitions($io);

        return Command::SUCCESS;
    }

    private function createFile(SymfonyStyle $io, string $file): void
    {
        $path = dirname(__DIR__, 2) . '/data/typespec/' . $file;
        $content = file_get_contents($path);
        $path = dirname(__DIR__, 5) . '/' . $file;
        $exists = file_exists($path);

        if (!$exists) {
            $io->writeln('Creating ' . $file);
            file_put_contents($path, $content);
        } else {
            $io->warning($file . ' already exists.');
        }
    }

    private function createSpecFolderAndFiles(SymfonyStyle $io): void
    {
        $projectRoot = dirname(__DIR__, 5);
        $spec = $projectRoot . '/spec';
        $models = $projectRoot . '/spec/models';
        $payloads = $projectRoot . '/spec/payloads';
        $responses = $projectRoot . '/spec/responses';
        $paths = [
            $spec, $models, $payloads, $responses
        ];
        $io->writeln('Creating node project files.');

        foreach ($paths as $path) {
            $this->makeDir($io, $path);
        }

        $files = [
            '.nvmrc',
            'package.json',
            'tspconfig.yaml',
            'spec/main.tsp',
            'spec/models/common.tsp',
            'spec/models/index.tsp',
            'spec/payloads/index.tsp',
            'spec/responses/errors.tsp',
            'spec/responses/index.tsp',
        ];

        foreach ($files as $file) {
            $this->createFile($io, $file);
        }
    }

    private function makeDir(SymfonyStyle $io, string $path): void
    {
        $exists = is_dir($path);

        if (!$exists) {
            $io->writeln('Creating ' . $path . '..');

            if (!mkdir($path) && !is_dir($path)) {
                $io->error(sprintf('Directory "%s" was not created', $path));
            }
        } else {
            $io->warning('Directory ' . $path . ' already exists.');
        }
    }
}
