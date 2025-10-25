<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Servicio para verificar respuestas de CAPTCHA (hCaptcha/reCAPTCHA).
 * Lee la configuración desde las variables de entorno.
 */
class CaptchaService
{
    private bool $enabled;
    private string $provider;
    private string $secretKey;
    
    private const RECAPTCHA_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
    private const HCAPTCHA_VERIFY_URL = 'https://hcaptcha.com/siteverify';

    public function __construct()
    {
        $this->enabled = filter_var($_ENV['CAPTCHA_ENABLED'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $this->provider = $_ENV['CAPTCHA_PROVIDER'] ?? 'recaptcha';
        $this->secretKey = $_ENV['CAPTCHA_SECRET_KEY'] ?? '';
    }

    /**
     * Verifica si el CAPTCHA está activado en la configuración.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Valida la respuesta del CAPTCHA enviada por el usuario.
     *
     * @param string $responseToken El token de respuesta del widget de CAPTCHA (ej. 'g-recaptcha-response').
     * @param string|null $remoteIp La IP del usuario (opcional pero recomendado).
     * @return bool True si la validación es exitosa, false en caso contrario.
     */
    public function verify(string $responseToken, ?string $remoteIp = null): bool
    {
        if (!$this->isEnabled()) {
            return true; // Si está desactivado, siempre es válido.
        }

        if (empty($responseToken) || empty($this->secretKey)) {
            return false;
        }

        $verifyUrl = ($this->provider === 'hcaptcha') ? self::HCAPTCHA_VERIFY_URL : self::RECAPTCHA_VERIFY_URL;

        $data = [
            'secret' => $this->secretKey,
            'response' => $responseToken,
        ];

        if ($remoteIp) {
            $data['remoteip'] = $remoteIp;
        }

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ],
        ];

        $context = stream_context_create($options);
        $result = @file_get_contents($verifyUrl, false, $context);

        if ($result === false) {
            error_log('CaptchaService Error: Failed to connect to verification server.');
            return false;
        }

        $response = json_decode($result, true);

        return isset($response['success']) && $response['success'] === true;
    }
}