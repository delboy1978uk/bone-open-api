<?php

namespace Bone\OpenApi\Console;

use Bone\OAuth2\Entity\Scope;
use Bone\OAuth2\Repository\ScopeRepository;

class DocGeneratorCommand
{
    /**
     * configure options
     */
    protected function configure()
    {
        $this->setName('docs');
        $this->setDescription('Generate Open API docs');
        $this->setHelp('Scans source code fow swagger php annotations');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('gaaarrrr...');

    }
}