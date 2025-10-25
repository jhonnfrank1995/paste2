<?php

declare(strict_types=1);

return [
/*
|--------------------------------------------------------------------------
| Driver de Caché por Defecto
|--------------------------------------------------------------------------
| Aquí puedes especificar cuál de los drivers de caché definidos a continuación
| se debe utilizar por defecto. Este driver se usa principalmente para
| el Rate Limiting.
| Soportados: "redis", "file" (fallback a un array en memoria para desarrollo).
*/
'driver' => $_ENV['CACHE_DRIVER'] ?? 'file',

/*
|--------------------------------------------------------------------------
| Configuración de Redis
|--------------------------------------------------------------------------
| Configuración para el servidor Redis utilizado por el Rate Limiter.
*/
'redis' => [
'host' => $_ENV['REDIS_HOST'] ?? '127.0.0.1',
'password' => $_ENV['REDIS_PASSWORD'] ?? null,
'port' => $_ENV['REDIS_PORT'] ?? 6379,
'database' => 0,
],
];