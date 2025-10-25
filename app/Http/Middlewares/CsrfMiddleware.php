<?php

declare(strict_types=1);

namespace App\Http\Middlewares;

use App\Core\Request;
use App\Core\Session;
use App\Core\Response;
use App\Core\View;

/**
 * Middleware para proteger contra ataques de Cross-Site Request Forgery (CSRF).
 * Valida el token CSRF para todas las peticiones que no sean 'GET'.
 */
class CsrfMiddleware
{
    /**
     * Maneja la petición entrante.
     */
    public function handle(): void
    {
        $request = new Request();
        $session = new Session();

        // El middleware solo actúa sobre métodos que pueden modificar estado.
        $safeMethods = ['GET', 'HEAD', 'OPTIONS'];
        
        if (in_array($request->method(), $safeMethods)) {
            return; // No se necesita validación para métodos seguros
        }

        $tokenFromRequest = $request->input('_csrf');
        $tokenFromSession = $session->getCsrfToken();

        if (!$tokenFromRequest || !hash_equals($tokenFromSession, $tokenFromRequest)) {
            // Si la validación falla, se muestra una página de error.
            // Esto previene la ejecución de la acción no autorizada.
            (new Response())->setStatusCode(419); // 419 Page Expired
            View::render('errors.419', ['message' => 'Page Expired. Please try again.']);
            exit();
        }
    }
}