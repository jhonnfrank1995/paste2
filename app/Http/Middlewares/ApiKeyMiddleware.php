<?php

declare(strict_types=1);

namespace App\Http\Middlewares;

use App\Core\Request;
use App\Core\Response;
use App\Models\ApiKey;

/**
 * Middleware para proteger las rutas de la API.
 * Valida la API Key proporcionada en la cabecera 'Authorization: Bearer'.
 */
class ApiKeyMiddleware
{
    /**
     * Maneja la petición entrante.
     */
    public function handle(): void
    {
        $request = new Request();
        $response = new Response();
        
        $token = $request->getBearerToken();

        if (!$token) {
            $response->json(['error' => 'Authentication required. API key is missing.'], 401);
            exit();
        }

        $apiKeyModel = new ApiKey();
        $user = $apiKeyModel->findUserByApiKey($token);

        if (!$user) {
            $response->json(['error' => 'Unauthorized. Invalid API key.'], 401);
            exit();
        }
        
        // Opcional: Adjuntar el usuario autenticado a un contexto global de la petición
        // para que los controladores de la API puedan usarlo.
        // App::set('api_user', $user);
    }
}