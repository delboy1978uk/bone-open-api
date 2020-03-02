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

    /**
     * ApiDocsController constructor.
     * @param string $docJsonPath
     */
    public function __construct(string $docJsonPath)
    {
        $this->docJsonPath = $docJsonPath;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array $args
     * @return ResponseInterface
     */
    public function apiDocsAction(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $body = $this->getView()->render('open-api::docs', []);

        return new HtmlResponse($body);
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
