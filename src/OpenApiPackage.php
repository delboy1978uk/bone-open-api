<?php

declare(strict_types=1);

namespace Bone\OpenApi;

use Barnacle\Container;
use Barnacle\RegistrationInterface;
use Bone\Controller\Init;
use Bone\Router\Router;
use Bone\Router\RouterConfigInterface;
use Bone\View\ViewEngine;
use Bone\OpenApi\Controller\ApiDocsController;
use Bone\Mail\Service\MailService;
use Bone\User\Controller\BoneUserController;

class OpenApiPackage implements RegistrationInterface, RouterConfigInterface
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
        $router->map('GET', '/api/docs', [ApiDocsController::class, 'apiDocsAction']);
        $router->map('GET', '/api/docs.json', [ApiDocsController::class, 'apiAction']);
    }
}
