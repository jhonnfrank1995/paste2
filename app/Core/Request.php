<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Encapsula la información de la petición HTTP.
 */
class Request
{
    public function uri(): string
    {
        return strtok($_SERVER['REQUEST_URI'], '?');
    }

    public function method(): string
    {
        return strtoupper($_POST['_method'] ?? $_SERVER['REQUEST_METHOD']);
    }

    public function input(string $key, $default = null): mixed
    {
        // Prioriza POST sobre GET
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    public function all(): array
    {
        return $_REQUEST;
    }
    
    public function file(string $key): ?array
    {
        return $_FILES[$key] ?? null;
    }

    public function getHeader(string $name): ?string
    {
        $name = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        return $_SERVER[$name] ?? null;
    }

    public function getBearerToken(): ?string
    {
        $header = $this->getHeader('Authorization');
        if ($header && preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function ip(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    public function userAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    }
}