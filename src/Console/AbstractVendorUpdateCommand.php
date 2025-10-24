<?php

namespace Bone\OpenApi\Console;

use Bone\Console\Command;
use Bone\Contracts\Container\ApiDocProviderInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use function file_exists;
use function file_put_contents;


abstract class AbstractVendorUpdateCommand extends Command
{
    public function __construct(
        private array $packages,
        string $name
    ) {
        parent::__construct($name);
    }

    protected function importPackageDefinitions(SymfonyStyle $io): void
    {
        $modelsTypeSpec = $routesTypeSpec = $payloadsTypeSpec = $responsesTypeSpec = "// this file is auto-generated, do not edit it.\n";

        foreach ($this->packages as $package) {
            $instance = new $package();
            $info = [];

            if ($instance instanceof ApiDocProviderInterface) {
                $info[] = 'Adding ' . $package . ' docs..';
                $models = $instance->provideModels();

                foreach ($models as $model) {
                    $modelsTypeSpec .= 'import "' . $model . '";' . "\n";
                }

                $routes = $instance->provideRoutes();

                foreach ($routes as $route) {
                    $routesTypeSpec .= 'import "' . $route . '";' . "\n";
                }

                $responses = $instance->provideResponses();

                foreach ($responses as $response) {
                    $responsesTypeSpec .= 'import "' . $response . '";' . "\n";
                }

                $payloads = $instance->providePayloads();

                foreach ($payloads as $payload) {
                    $payloadsTypeSpec .= 'import "' . $payload . '";' . "\n";
                }
            }

            $info ? $io->info($info) : null;
        }

        $modelFile = 'spec/models/vendors.tsp';
        $routeFile = 'spec/vendors.tsp';
        $responsesFile = 'spec/responses/vendors.tsp';
        $payloadsFile = 'spec/payloads/vendors.tsp';

        if (file_exists($modelFile)) {
            unlink($modelFile);
        }

        if (file_exists($routeFile)) {
            unlink($routeFile);
        }

        if (file_exists($responsesFile)) {
            unlink($responsesFile);
        }

        if (file_exists($payloadsFile)) {
            unlink($payloadsFile);
        }

        file_put_contents($modelFile, $modelsTypeSpec);
        file_put_contents($routeFile, $routesTypeSpec);
        file_put_contents($payloadsFile, $payloadsTypeSpec);
        file_put_contents($responsesFile, $responsesTypeSpec);
        $io->info(['auto-generating files..', $modelFile, $routeFile, $payloadsFile, $responsesFile]);
        $io->success([
            'Setup complete, please install using pnpm|npm|yarn. To compile docs, run',
            'pnpm run docs',
            'Docs available on https://' . \getenv('DOMAIN_NAME') . '/api/docs',
        ]);
    }
}
