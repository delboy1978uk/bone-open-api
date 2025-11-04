<?php

declare(strict_types=1);

namespace Bone\OpenApi;

use Barnacle\Container;
use Barnacle\RegistrationInterface;
use Bone\Application;
use Bone\Console\Command;
use Bone\Console\CommandRegistrationInterface;
use Bone\Contracts\Container\DefaultSettingsProviderInterface;
use Bone\Contracts\Container\DependentPackagesProviderInterface;
use Bone\Contracts\Container\PostFixturesProviderInterface;
use Bone\Controller\Init;
use Bone\Http\Middleware\DevOnlyMiddleware;
use Bone\OAuth2\Entity\Client;
use Bone\OpenApi\Console\ApiDocSetupCommand;
use Bone\OpenApi\Console\UpdateVendorsCommand;
use Bone\Router\Router;
use Bone\Router\RouterConfigInterface;
use Bone\OpenApi\Controller\ApiDocsController;
use Bone\View\ViewEngineInterface;
use Del\Booty\AssetRegistrationInterface;
use Doctrine\ORM\EntityManagerInterface;
use League\Route\RouteGroup;
use Symfony\Component\Console\Style\SymfonyStyle;

class OpenApiPackage implements RegistrationInterface, RouterConfigInterface, CommandRegistrationInterface,
                                AssetRegistrationInterface, DefaultSettingsProviderInterface, PostFixturesProviderInterface,
                                DependentPackagesProviderInterface
{
    public function addToContainer(Container $c): void
    {
        $viewEngine = $c->get(ViewEngineInterface::class);
        $viewEngine->addFolder('open-api', __DIR__ . '/View/');

        $c[ApiDocsController::class] = $c->factory(function (Container $c) {
            $docJsonPath = $c->has('docs') ? $c->get('docs') : 'data/docs/openapi.yaml';
            $swaggerClientCredentials = $c->has('swaggerClient') ? $c->get('swaggerClient') : [
                'clientId' => '',
                'clientSecret' => '',
            ];

            return  Init::controller(new ApiDocsController($docJsonPath, $swaggerClientCredentials), $c);
        });
    }

    public function addRoutes(Container $c, Router $router): void
    {
        $devOnly = new DevOnlyMiddleware();
        $router->group('/api', function (RouteGroup $route) {
            $route->map('GET', '/docs', [ApiDocsController::class, 'apiDocsAction']);
            $route->map('POST', '/docs', [ApiDocsController::class, 'apiDocsAction']);
            $route->map('GET', '/docs/open-api', [ApiDocsController::class, 'apiAction']);
        })->middleware($devOnly);
    }

    public function registerConsoleCommands(Container $container): array
    {
        $packages = $container->get('packages');
        $apiSetup = new ApiDocSetupCommand($packages, 'docs:setup');
        $vendorUpdate = new UpdateVendorsCommand($packages, 'docs:vendor-update');

        return [$apiSetup, $vendorUpdate];
    }

    public function getAssetFolders(): array
    {
        return [
            'docs' => dirname(__DIR__) . '/data/assets/docs',
        ];
    }

    public function getSettingsFileName(): string
    {
        return __DIR__ . '/../data/config/bone-open-api.php';
    }

    public function postFixtures(Command $command, SymfonyStyle $io): void
    {
        $io->writeln('Setting up Swagger Client');
        $config = file_get_contents($this->getSettingsFileName());
        $container = Application::ahoy()->bootstrap();
        $em = $container->get(EntityManagerInterface::class);
        $swaggerClient = $em->getRepository(Client::class)->findOneBy(['name' => 'API Docs']);
        $id = $swaggerClient->getIdentifier();
        $secret = $swaggerClient->getSecret();
        $regex = '#\'clientId\'\s=>\s\'\'#';
        $replacement = "'clientId' => '$id'";
        $config = \preg_replace($regex, $replacement, $config);
        $regex = '#\'clientSecret\'\s=>\s\'\'#';
        $replacement = "'clientSecret' => '$secret'";
        $config = \preg_replace($regex, $replacement, $config);
        file_put_contents('config/bone-open-api.php', $config);

        $io->writeln('Setting up a Typespec project');
        $command->runProcess($io, ['bone', 'docs:setup']);

        $io->success(['Typespec project has been installed.',]);
        $io->warning(['Please run `pnpm install` (or npm, or yarn) to setup the Node project']);
        $io->warning(['Then run `pnpm run docs` to compile your Typespec into the OpenAPI documentation']);
    }

    public function getRequiredPackages(): array
    {
        return [
            'Bone\Mail\MailPackage',
            'Bone\BoneDoctrine\BoneDoctrinePackage',
            'Bone\Paseto\PasetoPackage',
            'Del\Person\PersonPackage',
            'Del\UserPackage',
            'Del\Passport\PassportPackage',
            'Bone\Passport\PassportPackage',
            'Bone\User\BoneUserPackage',
            'Bone\OAuth2\BoneOAuth2Package',
            self::class,
        ];
    }
}
