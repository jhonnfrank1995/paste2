<?php

declare(strict_types=1);

namespace App\Core;

/**
* Router simple. En el proyecto final, será reemplazado por FastRoute,
* pero esta clase ilustra el concepto. El bootstrap usará FastRoute.
*/
class Router
{
protected array $routes = [];

public function add(string $method, string $uri, array $controllerAction): void
{
$this->routes[] = [
'method' => strtoupper($method),
'uri' => $uri,
'action' => $controllerAction,
];
}

// Métodos de conveniencia
public function get(string $uri, array $action) { $this->add('GET', $uri, $action); }
public function post(string $uri, array $action) { $this->add('POST', $uri, $action); }

// El método dispatch() sería el encargado de encontrar la ruta y ejecutar el controlador.
// Esta lógica estará en /public/index.php usando FastRoute.
}