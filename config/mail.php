<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;

return [
/*
|--------------------------------------------------------------------------
| Driver de Correo
|--------------------------------------------------------------------------
| PasteX Pro soporta el envío de correos a través de SMTP.
| Aquí puedes configurar el driver por defecto.
*/
'driver' => $_ENV['MAIL_DRIVER'] ?? 'smtp',

/*
|--------------------------------------------------------------------------
| Configuración del Host SMTP
|--------------------------------------------------------------------------
*/
'host' => $_ENV['MAIL_HOST'] ?? 'smtp.mailgun.org',
'port' => $_ENV['MAIL_PORT'] ?? 587,
'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls', // 'tls', 'ssl' o null

/*
|--------------------------------------------------------------------------
| Credenciales de Autenticación SMTP
|--------------------------------------------------------------------------
*/
'username' => $_ENV['MAIL_USERNAME'],
'password' => $_ENV['MAIL_PASSWORD'],

/*
|--------------------------------------------------------------------------
| Dirección "De" (From)
|--------------------------------------------------------------------------
| Puedes configurar la dirección de correo y el nombre que se utilizarán
| por defecto en todos los correos enviados por la aplicación.
*/
'from' => [
'address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'hello@example.com',
'name' => $_ENV['MAIL_FROM_NAME'] ?? 'PasteX Pro',
],
];