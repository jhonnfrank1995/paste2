<?php

declare(strict_types=1);

require __DIR__ . '/../bootstrap/app.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    
    // ---- RUTAS WEB ----
    $r->get('/', ['App\Http\Controllers\PasteController', 'home']);
    $r->get('/new', ['App\Http\Controllers\PasteController', 'create']);
    $r->post('/pastes', ['App\Http\Controllers\PasteController', 'store']);
    $r->get('/p/{id}', ['App\Http\Controllers\PasteController', 'show']);
    $r->post('/p/{id}/unlock', ['App\Http\Controllers\PasteController', 'unlock']);
    $r->get('/raw/{id}', ['App\Http\Controllers\PasteController', 'raw']);
    
    // Rutas de Autenticación
    $r->get('/login', ['App\Http\Controllers\AuthController', 'showLoginForm']);
    $r->post('/login', ['App\Http\Controllers\AuthController', 'login']);
    $r->get('/register', ['App\Http\Controllers\AuthController', 'showRegisterForm']);
    $r->post('/register', ['App\Http\Controllers\AuthController', 'register']);
    $r->post('/logout', ['App\Http\Controllers\AuthController', 'logout']);

    // Rutas de Usuario
    $r->get('/user/dashboard', ['App\Http\Controllers\UserController', 'dashboard']);
    $r->get('/user/profile', ['App\Http\Controllers\UserController', 'editProfile']);
    $r->post('/user/profile', ['App\Http\Controllers\UserController', 'updateProfile']);
    $r->get('/user/password', ['App\Http\Controllers\UserController', 'showChangePasswordForm']);
    $r->post('/user/password', ['App\Http\Controllers\UserController', 'updatePassword']);

    // ---- RUTAS DE ADMINISTRACIÓN ----
    $r->addGroup('/admin', function (RouteCollector $r) {
        $r->get('', ['App\Http\Controllers\Admin\DashboardController', 'index']);
        
        // Rutas de Gestión de Pastes
        $r->get('/pastes', ['App\Http\Controllers\Admin\PasteManagementController', 'index']);
        $r->get('/pastes/edit/{id}', ['App\Http\Controllers\Admin\PasteManagementController', 'edit']);
        $r->post('/pastes/update/{id}', ['App\Http\Controllers\Admin\PasteManagementController', 'update']);
        $r->post('/pastes/delete/{id}', ['App\Http\Controllers\Admin\PasteManagementController', 'destroy']);

        // Rutas de Gestión de Usuarios
        $r->get('/users', ['App\Http\Controllers\Admin\UserManagementController', 'index']);
        $r->get('/users/edit/{id:\d+}', ['App\Http\Controllers\Admin\UserManagementController', 'edit']);
        $r->post('/users/update/{id:\d+}', ['App\Http\Controllers\Admin\UserManagementController', 'update']);

        // Rutas de Configuración y Reportes
        $r->get('/settings', ['App\Http\Controllers\Admin\SettingsController', 'index']);
        $r->post('/settings', ['App\Http\Controllers\Admin\SettingsController', 'save']);
        $r->get('/reports', ['App\Http\Controllers\Admin\ReportController', 'index']);
    });

    // ---- RUTAS DE LA API ----
    $r->addGroup('/api/v1', function (RouteCollector $r) {
        $r->get('/pastes/{id}', ['App\Http\Controllers\Api\PasteApiController', 'getPaste']);
        $r->post('/pastes', ['App\Http\Controllers\Api\PasteApiController', 'createPaste']);
        $r->delete('/pastes/{id}', ['App\Http\Controllers\Api\PasteApiController', 'deletePaste']);
    });
});

// ... (El resto del archivo no cambia, pero se incluye completo)
$httpMethod = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
$uri = rawurldecode(strtok($_SERVER['REQUEST_URI'], '?'));
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404); App\Core\View::render('errors.404'); break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405); App\Core\View::render('errors.405', ['allowedMethods' => $routeInfo[1]]); break;
    case FastRoute\Dispatcher::FOUND:
        [$controllerClass, $method] = $routeInfo[1]; $vars = $routeInfo[2];
        $middlewares = [
            'modifying_methods' => \App\Http\Middlewares\CsrfMiddleware::class,
            '/admin' => \App\Http\Middlewares\AdminMiddleware::class, '/user/' => \App\Http\Middlewares\AuthMiddleware::class,
            '/login' => \App\Http\Middlewares\GuestMiddleware::class, '/register' => \App\Http\Middlewares\GuestMiddleware::class,
            '/api/' => \App\Http\Middlewares\ApiKeyMiddleware::class,
        ];
        if (in_array($httpMethod, ['POST', 'PUT', 'PATCH', 'DELETE'])) { (new $middlewares['modifying_methods'])->handle(); }
        foreach ($middlewares as $path => $middlewareClass) {
            if ($path !== 'modifying_methods' && str_starts_with($uri, $path)) { (new $middlewareClass())->handle(); }
        }
        $controller = new $controllerClass(); call_user_func_array([$controller, $method], $vars); break;
}