<?php

declare(strict_types=1);

namespace Bone\OpenApi;

use Barnacle\Container;
use Barnacle\RegistrationInterface;
use Bone\Mvc\Controller\Init;
use Bone\Mvc\Router\RouterConfigInterface;
use Bone\Mvc\View\PlatesEngine;
use Bone\OpenApi\Controller\ApiDocsController;
use BoneMvc\Mail\Service\MailService;
use BoneMvc\Module\BoneMvcUser\Controller\BoneMvcUserController;
use League\Route\Router;

class OpenApiPackage implements RegistrationInterface, RouterConfigInterface
{
    /**
     * @param Container $c
     */
    public function addToContainer(Container $c)
    {
        /** @var PlatesEngine $viewEngine */
        $viewEngine = $c->get(PlatesEngine::class);
        $viewEngine->addFolder('open-api', __DIR__ . '/View/');

        $c[ApiDocsController::class] = $c->factory(function (Container $c) {
            $docJsonPath = $c->has('docs') ? $c->get('docs') : 'data/docs/api.json';
            
            return  Init::controller(new ApiDocsController($docJsonPath), $c);
        });
    }

    /**
     * @return string
     */
    public function getEntityPath(): string
    {
        return '';
    }

    /**
     * @return bool
     */
    public function hasEntityPath(): bool
    {
        return false;
    }

    /**
     * @param Container $c
     * @param Router $router
     */
    public function addRoutes(Container $c, Router $router)
    {
        $router->map('GET', '/docsx', [ApiDocsController::class, 'apiDocsAction']);
        $router->map('GET', '/docs/api.json', [ApiDocsController::class, 'apiAction']);
    }
}
