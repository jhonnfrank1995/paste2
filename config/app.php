<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Nombre de la Aplicación
    |--------------------------------------------------------------------------
    | Este valor es el nombre de tu aplicación. Se utiliza cuando el framework
    | necesita colocar el nombre de la aplicación en una notificación o
    | en cualquier otro lugar según sea necesario para la aplicación o sus paquetes.
    */
    'name' => $_ENV['APP_NAME'] ?? 'PasteX Pro',

    /*
    |--------------------------------------------------------------------------
    | Entorno de la Aplicación
    |--------------------------------------------------------------------------
    | Este valor determina el "entorno" en el que se está ejecutando actualmente
    | tu aplicación. Esto puede determinar cómo se configuran varios servicios.
    | Valores válidos: 'development', 'production', 'testing'.
    */
    'env' => $_ENV['APP_ENV'] ?? 'production',

    /*
    |--------------------------------------------------------------------------
    | Modo de Depuración de la Aplicación
    |--------------------------------------------------------------------------
    | Cuando tu aplicación está en modo de depuración, se mostrarán mensajes de
    | error detallados con trazas de la pila en cada error que ocurra.
    | Si está deshabilitado, se muestra una página de error genérica.
    */
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),

    /*
    |--------------------------------------------------------------------------
    | URL de la Aplicación
    |--------------------------------------------------------------------------
    | El framework utiliza esta URL para generar correctamente las URLs cuando se
    | utilizan herramientas de línea de comandos o en otras partes de la aplicación.
    */
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',

    /*
    |--------------------------------------------------------------------------
    | Zona Horaria de la Aplicación
    |--------------------------------------------------------------------------
    | Aquí puedes especificar la zona horaria por defecto para tu aplicación,
    | que será utilizada por las funciones de fecha y hora de PHP.
    */
    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Clave de la Aplicación (APP_KEY)
    |--------------------------------------------------------------------------
    | Esta clave se utiliza para fines de firma general y debe ser una cadena
    | aleatoria de 32 bytes, codificada en base64. ¡NO LA COMPARTAS!
    */
    'key' => $_ENV['APP_KEY'],

    /*
    |--------------------------------------------------------------------------
    | Clave de Encriptación (ENCRYPTION_KEY)
    |--------------------------------------------------------------------------
    | Esta clave se utiliza para la encriptación simétrica (AES-256-GCM) de los
    | pastes en el servidor. Debe ser una cadena aleatoria de 32 bytes,
    | codificada en base64.
    */
    'cipher_key' => $_ENV['ENCRYPTION_KEY'],
];