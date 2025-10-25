<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Core\Controller;
use App\Models\Paste;
use App\Models\User;
use App\Models\ApiKey;

/**
 * Controlador para la API REST pública de PasteX Pro.
 * Todas las acciones aquí asumen que ApiKeyMiddleware ya ha validado el token.
 */
class PasteApiController extends Controller
{
    private Paste $pasteModel;
    private ?array $apiUser = null;

    public function __construct()
    {
        parent::__construct();
        $this->pasteModel = $this->model('Paste');
        $this->loadApiUser();
    }

    /**
     * Carga la información del usuario asociado a la API Key.
     */
    private function loadApiUser(): void
    {
        $token = $this->request->getBearerToken();
        if ($token) {
            $apiKeyModel = $this->model('ApiKey');
            $this->apiUser = $apiKeyModel->findUserByApiKey($token);
            // Marcar la API Key como usada recientemente
            if ($this->apiUser) {
                $apiKeyModel->updateLastUsed($token);
            }
        }
    }

    /**
     * Endpoint: GET /api/pastes/{id}
     * Obtiene los detalles de un paste específico.
     */
    public function getPaste(string $id)
    {
        $paste = $this->pasteModel->findById($id);

        if (!$paste) {
            return $this->response->json(['error' => 'Paste not found.'], 404);
        }

        // Lógica de acceso para la API
        if ($paste['visibility'] === 'private' && $paste['user_id'] !== $this->apiUser['id']) {
            return $this->response->json(['error' => 'You do not have permission to view this paste.'], 403);
        }
        
        if ($paste['has_password']) {
            return $this->response->json(['error' => 'This paste is password protected and cannot be accessed via API.'], 403);
        }

        // Limpiar datos sensibles antes de devolverlos
        unset($paste['password_hash'], $paste['edit_token'], $paste['deleted_at']);

        return $this->response->json($paste);
    }

    /**
     * Endpoint: POST /api/pastes
     * Crea un nuevo paste.
     */
    public function createPaste()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $rules = [
            'content' => 'required',
            'title' => 'max:255',
            'language' => 'max:50',
            'visibility' => 'in:public,unlisted,private'
        ];
        
        // El validador se podría extender para manejar arrays de entrada
        if (!isset($input['content']) || empty($input['content'])) {
             return $this->response->json(['errors' => ['content' => 'The content field is required.']], 422);
        }
        
        $visibility = $input['visibility'] ?? 'unlisted';
        if ($visibility === 'private' && !$this->apiUser) {
            return $this->response->json(['error' => 'You must use a valid user API key to create private pastes.'], 403);
        }
        
        $data = [
            'user_id' => $this->apiUser['id'] ?? null,
            'title' => $input['title'] ?? 'Untitled API Paste',
            'content' => $input['content'],
            'language' => $input['language'] ?? 'plaintext',
            'visibility' => $visibility,
            'expiration' => $input['expiration'] ?? '1d', // Expiración por defecto para la API
            'password' => null, // La API no permite crear pastes con contraseña por simplicidad/seguridad
        ];

        $result = $this->pasteModel->create($data);

        if ($result['status'] === 'success') {
            $pasteUrl = rtrim($_ENV['APP_URL'], '/') . '/p/' . $result['id'];
            return $this->response->json([
                'id' => $result['id'],
                'url' => $pasteUrl,
                'raw_url' => $pasteUrl . '/raw'
            ], 201);
        } else {
            return $this->response->json(['error' => $result['message']], 500);
        }
    }

    /**
     * Endpoint: DELETE /api/pastes/{id}
     * Elimina un paste.
     */
    public function deletePaste(string $id)
    {
        $paste = $this->pasteModel->findById($id, false);

        if (!$paste) {
            return $this->response->json(['error' => 'Paste not found.'], 404);
        }

        if ($paste['user_id'] !== $this->apiUser['id']) {
            return $this->response->json(['error' => 'You do not have permission to delete this paste.'], 403);
        }

        if ($this->pasteModel->delete($id)) {
            return $this->response->json(['message' => 'Paste deleted successfully.']);
        } else {
            return $this->response->json(['error' => 'Failed to delete paste.'], 500);
        }
    }
}