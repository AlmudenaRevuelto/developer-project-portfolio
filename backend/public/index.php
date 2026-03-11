<?php

require_once __DIR__ . '/../src/Router/Router.php';
require_once __DIR__ . '/../src/Controller/ClientController.php';
require_once __DIR__ . '/../src/Controller/ProjectController.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// quitar prefijo /api
if (str_starts_with($uri, '/api')) {
    $uri = substr($uri, 4);
}

$uri = rtrim($uri, '/') ?: '/';

$router = new Router();

$clientController = new ClientController();
$projectController = new ProjectController();

/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
*/

$router->add('GET', '/clients', [$clientController, 'index']);
$router->add('GET', '/clients/{id}', [$clientController, 'show']);
$router->add('POST', '/clients', [$clientController, 'store']);
$router->add('PUT', '/clients/{id}', [$clientController, 'update']);
$router->add('DELETE', '/clients/{id}', [$clientController, 'destroy']);

$router->add('GET', '/clients/{id}/projects', [$projectController, 'indexByClient']);

/*
|--------------------------------------------------------------------------
| Project Routes
|--------------------------------------------------------------------------
*/

$router->add('GET', '/projects', [$projectController, 'index']);
$router->add('GET', '/projects/{id}', [$projectController, 'show']);
$router->add('POST', '/projects', [$projectController, 'store']);
$router->add('PUT', '/projects/{id}', [$projectController, 'update']);
$router->add('DELETE', '/projects/{id}', [$projectController, 'destroy']);

$router->dispatch($method, $uri);