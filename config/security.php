<?php

declare(strict_types=1);

return [
/*
|--------------------------------------------------------------------------
| Duración de la Sesión
|--------------------------------------------------------------------------
| Aquí puedes especificar el número de minutos que deseas que la sesión
| permanezca activa antes de que expire.
*/
'session_lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 120),

/*
|--------------------------------------------------------------------------
| Parámetros de Hashing de Contraseña
|--------------------------------------------------------------------------
| Configuración para el algoritmo Argon2id.
| Estos son valores seguros por defecto. No los cambies a menos que
| sepas lo que estás haciendo.
*/
'password_hashing' => [
'memory_cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
'threads' => PASSWORD_ARGON2_DEFAULT_THREADS,
],

/*
|--------------------------------------------------------------------------
| Configuración de Rate Limiting
|--------------------------------------------------------------------------
| Define los límites para varias acciones para prevenir abusos.
*/
'rate_limiter' => [
'login_attempts' => [
'max' => 5, // 5 intentos
'decay' => 60, // por minuto
],
'paste_creations' => [
'max' => 15, // 15 creaciones
'decay' => 60 * 60, // por hora (para usuarios anónimos)
],
'api' => [
'max' => 60, // 60 peticiones
'decay' => 60, // por minuto
],
],
];