<?php
declare(strict_types=1);
if (PHP_SAPI === 'cli-server') {
	$requestedFile = __DIR__ . parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
	if (is_file($requestedFile)) {
		$mimeType = mime_content_type($requestedFile) ?: 'application/octet-stream';
		header('Content-Type: ' . $mimeType);
		readfile($requestedFile);
		exit;
	}
}
require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/config/database.php';
use App\Application;
use DI\ContainerBuilder;
$builder = new ContainerBuilder();
$builder->addDefinitions(dirname(__DIR__) . '/config/container.php');
$container = $builder->build();
$container->get(Application::class)->run();