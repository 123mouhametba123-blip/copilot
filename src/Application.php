<?php
declare(strict_types=1);
namespace App;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Container\ContainerInterface;
use Throwable;
use function FastRoute\simpleDispatcher;

final class Application
{
    public function __construct(private ContainerInterface $container) {}
    public function run(): void
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $routes): void { (require dirname(__DIR__) . '/routes/web.php')($routes); });
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $result = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $path);
        if ($result[0] === Dispatcher::NOT_FOUND) { http_response_code(404); echo $this->container->get(\App\View\View::class)->render('error/404'); return; }
        if ($result[0] === Dispatcher::METHOD_NOT_ALLOWED) { http_response_code(405); header('Allow: ' . implode(', ', $result[1])); echo $this->container->get(\App\View\View::class)->render('error/405'); return; }
        [$handler, $vars] = [$result[1], $result[2]];
        $arguments = array_map(static fn (string $value): int|string => ctype_digit($value) ? (int) $value : $value, array_values($vars));
        try { echo $this->container->get($handler[0])->{$handler[1]}(...$arguments); }
        catch (Throwable $exception) { http_response_code(500); echo $this->container->get(\App\View\View::class)->render('error/500', ['message' => $exception->getMessage()]); }
    }
}