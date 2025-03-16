<?php declare(strict_types=1);

namespace Bone\OpenApi\Controller;

use Bone\Controller\Controller;
use Bone\Exception;
use Bone\Http\Response\YamlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use function strpos;

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
        $contents = file_get_contents($this->docPath);

        if (strpos($this->docPath, '.json') !== false) {
            $data = json_decode($json, true);

            return new JsonResponse($data);
        } elseif (
            strpos($this->docPath, '.yaml') !== false
            || strpos($this->docPath, '.yml') !== false
        ) {
            return new YamlResponse($contents);
        }

        return new Exception(Exception::LOST_AT_SEA);
    }
}
