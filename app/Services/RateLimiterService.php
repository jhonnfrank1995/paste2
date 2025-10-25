<?php

declare(strict_types=1);

namespace App\Services;

use Predis\Client as RedisClient;

/**
 * Servicio para limitar la tasa de peticiones (Rate Limiting).
 * Puede usar Redis (preferido) o un simple array en memoria como fallback (no para producción).
 */
class RateLimiterService
{
    private mixed $storage; // Puede ser Redis o un array
    private string $driver;

    public function __construct()
    {
        $this->driver = $_ENV['CACHE_DRIVER'] ?? 'file'; // Usaremos 'file' como 'array' por simplicidad aquí

        if ($this->driver === 'redis') {
            try {
                $this->storage = new RedisClient([
                    'scheme' => 'tcp',
                    'host'   => $_ENV['REDIS_HOST'] ?? '127.0.0.1',
                    'port'   => $_ENV['REDIS_PORT'] ?? 6379,
                ]);
                $this->storage->connect();
            } catch (\Exception $e) {
                error_log("RateLimiter Error: Could not connect to Redis. Falling back to array. Error: " . $e->getMessage());
                $this->driver = 'array';
                $this->storage = [];
            }
        } else {
            $this->storage = [];
        }
    }

    /**
     * Intenta consumir un "token" de un bucket. Si tiene éxito, la acción está permitida.
     * Implementa el algoritmo de "token bucket".
     *
     * @param string $key Una clave única para la acción y el identificador (ej. 'login_attempt_127.0.0.1').
     * @param int $maxAttempts Número máximo de intentos permitidos.
     * @param int $decaySeconds Tiempo en segundos para que los intentos "expiren".
     * @return bool True si la acción está permitida, false si se ha superado el límite.
     */
    public function attempt(string $key, int $maxAttempts, int $decaySeconds): bool
    {
        if ($this->driver === 'redis') {
            return $this->attemptRedis($key, $maxAttempts, $decaySeconds);
        }
        return $this->attemptArray($key, $maxAttempts, $decaySeconds);
    }
    
    private function attemptRedis(string $key, int $maxAttempts, int $decaySeconds): bool
    {
        $key = 'rate_limit:' . $key;
        $count = $this->storage->incr($key);
        
        if ($count == 1) {
            // Si es el primer intento, establecer la expiración de la clave.
            $this->storage->expire($key, $decaySeconds);
        }

        return $count <= $maxAttempts;
    }
    
    private function attemptArray(string $key, int $maxAttempts, int $decaySeconds): bool
    {
        // NOTA: Este método no es adecuado para producción, ya que el estado se
        // pierde entre peticiones. Sirve como un fallback de desarrollo.
        $now = time();
        if (!isset($this->storage[$key]) || ($now - $this->storage[$key]['time']) > $decaySeconds) {
            $this->storage[$key] = ['count' => 1, 'time' => $now];
            return true;
        }

        if ($this->storage[$key]['count'] < $maxAttempts) {
            $this->storage[$key]['count']++;
            return true;
        }
        
        return false;
    }
}