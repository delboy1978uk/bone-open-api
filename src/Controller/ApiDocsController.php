<?php declare(strict_types=1);

namespace Bone\OpenApi\Controller;

use Bone\Controller\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;


class ApiDocsController extends Controller
{
    /** @var string $docJsonPath */
    private $docJsonPath;

    /** @var array $swaggerClientCredentials */
    private $swaggerClientCredentials;

    /**
     * ApiDocsController constructor.
     * @param string $docJsonPath
     */
    public function __construct(string $docJsonPath, array $swaggerClientCredentials)
    {
        $this->docJsonPath = $docJsonPath;
        $this->swaggerClientCredentials = $swaggerClientCredentials;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array $args
     * @return ResponseInterface
     */
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

    /**
     * @param ServerRequestInterface $request
     * @param array $args
     * @return ResponseInterface
     */
    public function apiAction(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $json = file_get_contents($this->docJsonPath);
        $data = json_decode($json, true);

        return new JsonResponse($data);
    }
}
