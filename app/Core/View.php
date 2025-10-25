<?php

declare(strict_types=1);

namespace App\Core;

class View
{
/**
* Renderiza un archivo de vista con sus datos.
*
* @param string $view El nombre de la vista (ej. 'paste.show').
* @param array $data Datos para pasar a la vista.
*/
public static function render(string $view, array $data = []): void
{
$session = new Session();

// Datos globales disponibles en todas las vistas
$defaultData = [
'errors' => $session->getFlash('errors', []),
'old' => $session->getFlash('old', []),
'success_message' => $session->getFlash('success'),
'error_message' => $session->getFlash('error'),
'user' => $session->get('user'), // Información del usuario logueado
'csrf_token' => $session->getCsrfToken()
];

$data = array_merge($defaultData, $data);

extract($data, EXTR_SKIP);

$viewPath = str_replace('.', '/', $view);
$file = __DIR__ . "/../../resources/views/{$viewPath}.php";

if (!is_readable($file)) {
throw new \RuntimeException("View '{$view}' not found at path: {$file}");
}

// Headers de seguridad por defecto
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;");

ob_start();
require $file;
ob_end_flush();
}
}