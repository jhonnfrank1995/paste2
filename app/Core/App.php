<?php

declare(strict_types=1);

namespace App\Core;

use Exception;

/**
 * Un contenedor de servicios simple (Service Locator/Registry).
 * Almacena y recupera instancias o valores clave de la aplicación.
 */
class App
{
    /**
     * El registro de servicios.
     * @var array
     */
    protected static array $registry = [];

    /**
     * Vincula una clave a un valor en el contenedor.
     *
     * @param string $key La clave de identificación para el servicio o valor.
     * @param mixed $value El valor o la instancia a almacenar.
     */
    public static function bind(string $key, $value): void
    {
        static::$registry[$key] = $value;
    }

    /**
     * Recupera un valor del contenedor a través de su clave.
     *
     * @param string $key La clave del servicio o valor a recuperar.
     * @return mixed
     * @throws Exception si la clave no se encuentra en el contenedor.
     */
    public static function get(string $key)
    {
        if (!array_key_exists($key, static::$registry)) {
            throw new Exception("No service or value is bound with the key '{$key}' in the container.");
        }
        
        return static::$registry[$key];
    }
}