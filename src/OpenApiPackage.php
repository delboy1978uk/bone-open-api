<?php

declare(strict_types=1);

namespace Bone\OpenApi;

use Barnacle\Container;
use Barnacle\RegistrationInterface;
use Bone\Console\CommandRegistrationInterface;
use Bone\Controller\Init;
use Bone\OpenApi\Console\DocGeneratorCommand;
use Bone\Router\Router;
use Bone\Router\RouterConfigInterface;
use Bone\View\ViewEngine;
use Bone\OpenApi\Controller\ApiDocsController;
use Bone\Mail\Service\MailService;
use Bone\User\Controller\BoneUserController;

class OpenApiPackage implements RegistrationInterface, RouterConfigInterface, CommandRegistrationInterface
{
    /**
     * @param Container $c
     */
    public function addToContainer(Container $c)
    {
        /** @var ViewEngine $viewEngine */
        $viewEngine = $c->get(ViewEngine::class);
        $viewEngine->addFolder('open-api', __DIR__ . '/View/');

        $c[ApiDocsController::class] = $c->factory(function (Container $c) {
            $docJsonPath = $c->has('docs') ? $c->get('docs') : 'data/docs/api.json';
            
            return  Init::controller(new ApiDocsController($docJsonPath), $c);
        });
    }

    /**
     * @param Container $c
     * @param Router $router
     */
    public function addRoutes(Container $c, Router $router)
    {
        $router->map('GET', '/api/docs', [ApiDocsController::class, 'apiDocsAction']);
        $router->map('GET', '/api/docs.json', [ApiDocsController::class, 'apiAction']);
    }

    /**
     * @param Container $container
     * @return array
     */
    public function registerConsoleCommands(Container $container): array
    {
        $packages = $container->get('packages');
        $command = new DocGeneratorCommand($packages);
        $command->setName('docs:generate');

        return [$command];
    }
}
