<?php

declare(strict_types=1);

namespace App\Core;

class Session
{
    protected const FLASH_KEY = 'flash_messages';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            $config = require __DIR__ . '/../../config/security.php';
            session_set_cookie_params([
                'lifetime' => ($config['session_lifetime'] ?? 120) * 60,
                'path' => '/',
                'domain' => $_ENV['APP_URL'] ? parse_url($_ENV['APP_URL'], PHP_URL_HOST) : '',
                'secure' => ($_ENV['APP_ENV'] ?? 'production') === 'production',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            session_start();
        }
    }

    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }
    
    public function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public function destroy(): void
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    public function flash(string $key, mixed $message): void
    {
        $_SESSION[self::FLASH_KEY][$key] = $message;
    }

    // ===============================================================
    //  FIX DEFINITIVO: Cambiar el tipado de string a mixed
    // ===============================================================
    // La función ahora acepta y devuelve cualquier tipo de dato (mixed),
    // lo que le permite manejar tanto arrays de errores como mensajes
    // de texto simples.
    public function getFlash(string $key, mixed $default = null): mixed
    {
        $message = $_SESSION[self::FLASH_KEY][$key] ?? $default;
        unset($_SESSION[self::FLASH_KEY][$key]);
        return $message;
    }
    // ===============================================================

    public function getCsrfToken(): string
    {
        if (!$this->has('_csrf')) {
            $this->set('_csrf', bin2hex(random_bytes(32)));
        }
        return $this->get('_csrf');
    }
}