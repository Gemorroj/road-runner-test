<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


class IndexController extends AbstractController
{
    public function __invoke(ServerRequestInterface $request, array $params): ResponseInterface
    {
        return $this->makeJsonResponse([
            'text' => 'hello world',
            'request' => $request
        ]);
    }
}
