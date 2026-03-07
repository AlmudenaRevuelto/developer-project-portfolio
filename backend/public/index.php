<?php

require_once __DIR__ . '/../src/Controllers/ClientController.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Normalizar trailing slash
$uri = rtrim($uri, '/') ?: '/';

$controller = new ClientController();

/*
|--------------------------------------------------------------------------
| Clients Routes
|--------------------------------------------------------------------------
*/

// GET /clients
if ($uri === '/clients' && $method === 'GET') {
    $controller->list();
    return;
}

// GET /clients/{id}
if (preg_match('#^/clients/(\d+)$#', $uri, $matches) && $method === 'GET') {
    $controller->show((int) $matches[1]);
    return;
}

// POST /clients
if ($uri === '/clients' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $controller->create($data);
    return;
}

// DELETE /clients/{id}
if (preg_match('#^/clients/(\d+)$#', $uri, $matches) && $method === 'DELETE') {
    $controller->delete((int) $matches[1]);
    return;
}

// PUT /clients/{id}  (lo dejamos preparado)
if (preg_match('#^/clients/(\d+)$#', $uri, $matches) && $method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $controller->update((int) $matches[1], $data);
    return;
}

// Fallback
http_response_code(404);
header('Content-Type: application/json');
echo json_encode([
    'error' => 'Route not found',
    'path' => $uri,
    'method' => $method
]);