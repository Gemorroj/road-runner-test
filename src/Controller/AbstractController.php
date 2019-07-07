<?php

namespace App\Controller;

use Zend\Diactoros\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractController
{
    protected function makeJsonResponse(array $data): ResponseInterface
    {
        $resp = new Response('php://memory', 200, ['Content-Type' => 'application/json; charset=UTF-8']);

        $resp->getBody()->write(\json_encode(
            $data,
            JSON_THROW_ON_ERROR | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_PRESERVE_ZERO_FRACTION
        ));

        return $resp;
    }

    abstract public function __invoke(ServerRequestInterface $request, array $params): ResponseInterface;
}
