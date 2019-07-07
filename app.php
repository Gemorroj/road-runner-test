<?php
declare(strict_types=1);

\mb_internal_encoding('UTF-8');
\error_reporting(E_ALL | E_STRICT);
\ini_set('display_errors', 'stderr');

require __DIR__ . '/vendor/autoload.php';


$relay = new \Spiral\Goridge\StreamRelay(STDIN, STDOUT);
$psr7 = new \Spiral\RoadRunner\PSR7Client(new Spiral\RoadRunner\Worker($relay));


$dispatcher = \FastRoute\simpleDispatcher(static function(\FastRoute\RouteCollector $r): void {
    $r->addRoute('GET', '/', new \App\Controller\IndexController());
});


while ($req = $psr7->acceptRequest()) {
    try {
        $match = $dispatcher->dispatch($req->getMethod(), $req->getUri()->getPath());

        switch ($match[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                throw new \RuntimeException('Not found');
                break;

            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowed = (array) $match[1];
                throw new \RuntimeException(\sprintf('Method not allowed. Allowed %s', \implode(',', $allowed)));
                break;

            case \FastRoute\Dispatcher::FOUND:
            default:
                $resp = $match[1]($req, $match[2]);
                break;
        }

        $psr7->respond($resp);
    } catch (\Throwable $e) {
        $psr7->getWorker()->error((string)$e);
    }
}
