<?php

declare(strict_types=1);

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

/**
 * Servicio para el envío de correos electrónicos.
 * Es un wrapper sobre PHPMailer, configurado a través de mail.php.
 */
class MailerService
{
    private PHPMailer $mailer;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/mail.php';
        
        $this->mailer = new PHPMailer(true); // true para activar excepciones
        $this->mailer->isSMTP();
        $this->mailer->Host = $config['host'];
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $config['username'];
        $this->mailer->Password = $config['password'];
        $this->mailer->SMTPSecure = $config['encryption'];
        $this->mailer->Port = (int)$config['port'];
        $this->mailer->setFrom($config['from']['address'], $config['from']['name']);
        $this->mailer->isHTML(true);
        $this->mailer->CharSet = 'UTF-8';
    }

    /**
     * Envía un correo electrónico.
     *
     * @param string $to La dirección del destinatario.
     * @param string $subject El asunto del correo.
     * @param string $view El nombre de la plantilla de vista (sin .php) ubicada en resources/views/emails/.
     * @param array $data Datos para pasar a la plantilla de la vista.
     * @return bool True si el correo se envió con éxito, false en caso contrario.
     */
    public function send(string $to, string $subject, string $view, array $data = []): bool
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($to);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $this->renderView($view, $data);
            
            // Opcional: generar una versión en texto plano del HTML
            $this->mailer->AltBody = strip_tags($this->mailer->Body);
            
            return $this->mailer->send();
        } catch (PHPMailerException $e) {
            // Registrar el error detallado en los logs del servidor
            error_log("MailerService Error: " . $e->getMessage() . " | PHPMailer ErrorInfo: " . $this->mailer->ErrorInfo);
            return false;
        }
    }
    
    /**
     * Renderiza una plantilla de vista de correo a una cadena de texto.
     *
     * @param string $view El nombre de la vista.
     * @param array $data Los datos para la vista.
     * @return string El contenido HTML del correo.
     * @throws \Exception si la plantilla no se encuentra.
     */
    private function renderView(string $view, array $data): string
    {
        $path = __DIR__ . "/../../resources/views/emails/{$view}.php";
        if (!is_readable($path)) {
            throw new \Exception("Email view template not found at: {$path}");
        }
        
        // Extrae las variables para que estén disponibles en la vista
        extract($data);
        
        ob_start();
        include $path;
        return ob_get_clean();
    }
}