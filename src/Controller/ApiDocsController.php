<?php declare(strict_types=1);

namespace Bone\OpenApi\Controller;

use Bone\Controller\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;

class ApiDocsController extends Controller
{
    private string $docPath;
    private array $swaggerClientCredentials;

    public function __construct(string $docPath, array $swaggerClientCredentials)
    {
        $this->docPath = $docPath;
        $this->swaggerClientCredentials = $swaggerClientCredentials;
    }

    public function apiDocsAction(ServerRequestInterface $request, array $args): ResponseInterface
    {
        if ($request->getMethod() === 'GET') {
            $body = $this->getView()->render('open-api::docs', [
                'clientId' => $this->swaggerClientCredentials['clientId'],
                'clientSecret' => $this->swaggerClientCredentials['clientSecret'],
            ]);
            $response = new HtmlResponse($body, 200, ['layout' => 'none']);
        } else {
            $body = $request->getParsedBody();
            $response = new JsonResponse($body);
        }

        return $response;
    }

    public function apiAction(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $json = file_get_contents($this->docPath);
        $data = json_decode($json, true);

        return new JsonResponse($data);
    }
}
