<?php

declare(strict_types=1);

$basePath = dirname(dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
$basePath = str_replace('\\', '/', $basePath);
define('BASE_URL', $basePath === '/' ? '' : rtrim($basePath, '/'));

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';

    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = __DIR__ . '/../app/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

require __DIR__ . '/../app/helpers.php';

$route = trim((string) ($_GET['url'] ?? 'admin/create'), '/');
$segments = $route === '' ? ['admin', 'create'] : explode('/', $route);

$controllerName = 'App\\Controllers\\' . ucfirst($segments[0]) . 'Controller';
$method = $segments[1] ?? 'create';
$params = array_slice($segments, 2);

if (!class_exists($controllerName) || !method_exists($controllerName, $method)) {
    http_response_code(404);
    exit('Página não encontrada.');
}

$controller = new $controllerName();
$controller->$method(...$params);
