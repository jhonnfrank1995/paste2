<?php

declare(strict_types=1);

namespace App\Http\Middlewares;

use App\Core\Session;
use App\Core\Response;

/**
 * Middleware para rutas que solo deben ser accesibles por usuarios no autenticados (invitados).
 * Por ejemplo, las páginas de login o registro.
 * Si un usuario logueado intenta acceder, es redirigido a la página principal.
 */
class GuestMiddleware
{
    /**
     * Maneja la petición entrante.
     */
    public function handle(): void
    {
        $session = new Session();

        if ($session->has('user_id')) {
            (new Response())->redirect('/');
            exit();
        }
    }
}