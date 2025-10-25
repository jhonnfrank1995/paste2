<?php

declare(strict_types=1);

namespace App\Core;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

/**
 * Gestiona la carga de las variables de entorno desde el archivo .env.
 */
class Env
{
    /**
     * Carga el archivo .env desde la ruta especificada.
     *
     * Este método debe ser llamado una única vez al inicio de la aplicación
     * (en el archivo bootstrap/app.php).
     *
     * @param string $path La ruta absoluta al directorio que contiene el archivo .env.
     */
    public static function load(string $path): void
    {
        try {
            // Se utiliza createImmutable porque es seguro: no sobrescribe variables de entorno
            // que ya existan en el servidor, lo cual es ideal para producción.
            $dotenv = Dotenv::createImmutable($path);
            $dotenv->load();
        } catch (InvalidPathException $e) {
            // Esta es una condición de error fatal. La aplicación no puede funcionar sin
            // su configuración de entorno.
            error_log("Failed to load .env file: " . $e->getMessage());
            die("<strong>Critical Error:</strong> The .env file could not be found or is not readable. Please ensure the file exists and has the correct permissions. If this is a new installation, please run the installer.");
        }
    }
}