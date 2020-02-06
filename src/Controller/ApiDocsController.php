<?php declare(strict_types=1);

namespace Bone\OpenApi\Controller;

use Bone\Mvc\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;;

class ApiDocsController extends Controller
{
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
}
