<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

/**
 * Wrapper Singleton para la conexión PDO a la base de datos.
 * Lee la configuración desde las variables de entorno.
 */
class DB
{
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}
    public function __wakeup() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../config/database.php';
            $driver = $config['default'];
            $connection = $config['connections'][$driver];

            $dsn = '';
            $user = $connection['username'] ?? null;
            $pass = $connection['password'] ?? null;
            
            if ($driver === 'mysql') {
                $dsn = "mysql:host={$connection['host']};port={$connection['port']};dbname={$connection['database']};charset={$connection['charset']}";
            } elseif ($driver === 'sqlite') {
                $dsn = 'sqlite:' . $connection['path'];
                $user = null;
                $pass = null;
            } else {
                 throw new \Exception("Unsupported DB driver: {$driver}");
            }

            try {
                self::$instance = new PDO($dsn, $user, $pass, $connection['options']);
            } catch (PDOException $e) {
                // Nunca mostrar errores detallados en producción.
                error_log("Database Connection Error: " . $e->getMessage());
                // Lanzar una excepción genérica para que el manejador de errores la capture
                throw new PDOException("Service Unavailable: Could not connect to the database.", 503);
            }
        }
        return self::$instance;
    }

    /**
     * Permite llamar a métodos de PDO de forma estática (DB::prepare(...)).
     */
    public static function __callStatic($method, $args)
    {
        return self::getInstance()->{$method}(...$args);
    }
}