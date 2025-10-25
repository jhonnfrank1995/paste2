<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Un logger simple basado en archivos.
 * Escribe logs en storage/logs/app.log.
 * Proporciona diferentes niveles de log (INFO, WARNING, ERROR).
 */
class Logger
{
    private static string $logFilePath;
    private static bool $isInitialized = false;

    /**
     * Inicializa el logger, definiendo la ruta del archivo y asegurando que el directorio exista.
     */
    private static function initialize(): void
    {
        if (static::$isInitialized) {
            return;
        }

        $logDirectory = __DIR__ . '/../../storage/logs';
        
        if (!is_dir($logDirectory)) {
            // Intenta crear el directorio si no existe.
            if (!mkdir($logDirectory, 0775, true) && !is_dir($logDirectory)) {
                 // Si falla, no se pueden escribir logs. Desactivamos el logger para evitar errores.
                error_log('Logger Error: Could not create log directory: ' . $logDirectory);
                static::$isInitialized = true; // Marcamos como inicializado para no reintentar
                return;
            }
        }

        static::$logFilePath = $logDirectory . '/app.log';
        static::$isInitialized = true;
    }

    /**
     * El método central que escribe el mensaje en el archivo de log.
     *
     * @param string $level El nivel del log (e.g., INFO, ERROR).
     * @param string $message El mensaje a registrar.
     * @param array $context Datos adicionales para incluir en el log (se codifican en JSON).
     */
    public static function log(string $level, string $message, array $context = []): void
    {
        static::initialize();

        // Si la inicialización falló (ej. no se pudo crear el directorio), no hacemos nada.
        if (empty(static::$logFilePath)) {
            return;
        }

        $level = strtoupper($level);
        $date = date('Y-m-d H:i:s');
        $contextString = !empty($context) ? json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : '';

        $logEntry = "[{$date}] [{$level}] {$message} {$contextString}" . PHP_EOL;

        // FILE_APPEND para añadir al final del archivo.
        // LOCK_EX para prevenir que escrituras simultáneas corrompan el archivo.
        file_put_contents(static::$logFilePath, $logEntry, FILE_APPEND | LOCK_EX);
    }

    /**
     * Registra un mensaje de información.
     * Útil para eventos de rutina.
     */
    public static function info(string $message, array $context = []): void
    {
        static::log('info', $message, $context);
    }

    /**
     * Registra un mensaje de advertencia.
     * Útil para eventos no críticos que podrían indicar un problema futuro.
     */
    public static function warning(string $message, array $context = []): void
    {
        static::log('warning', $message, $context);
    }

    /**
     * Registra un mensaje de error.
     * Útil para errores de ejecución, excepciones y otros fallos.
     */
    public static function error(string $message, array $context = []): void
    {
        static::log('error', $message, $context);
    }
}