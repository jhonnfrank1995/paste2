<?php

declare(strict_types=1);

namespace App\Http\Middlewares;

use App\Core\Session;
use App\Core\Response;

/**
 * Middleware para proteger rutas que requieren que el usuario esté autenticado.
 * Si el usuario no está logueado, lo redirige a la página de login.
 */
class AuthMiddleware
{
    /**
     * Maneja la petición entrante.
     */
    public function handle(): void
    {
        $session = new Session();

        if (!$session->has('user_id')) {
            $session->flash('error', 'You must be logged in to view this page.');
            (new Response())->redirect('/login');
            exit();
        }
    }
}