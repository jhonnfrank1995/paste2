<?php

declare(strict_types=1);

namespace App.Services;

use App.Models.Setting;
use Exception;

/**
 * Servicio para gestionar y enviar webhooks.
 *
 * La configuración de los webhooks se almacena en la tabla 'settings'.
 * Se espera una clave como 'webhooks_urls' con un JSON que contiene arrays de URLs por evento.
 * Ejemplo de valor en la DB:
 * {
 *   "paste.created": ["https://example.com/hook1", "https://example.com/hook2"],
 *   "paste.deleted": ["https://example.com/hook3"]
 * }
 */
class WebhookService
{
    private array $webhookUrls = [];
    private bool $enabled = false;

    public function __construct()
    {
        $settingModel = new Setting();
        $this->enabled = (bool)$settingModel->get('webhooks_enabled', false);

        if ($this->enabled) {
            $urlsJson = $settingModel->get('webhooks_urls', '{}');
            $this->webhookUrls = json_decode($urlsJson, true) ?: [];
        }
    }

    /**
     * Dispara un evento y envía la carga útil (payload) a todas las URLs registradas para ese evento.
     *
     * @param string $eventName El nombre del evento (ej. 'paste.created').
     * @param array $payload Los datos a enviar en el cuerpo de la petición.
     */
    public function trigger(string $eventName, array $payload): void
    {
        if (!$this->enabled || !isset($this->webhookUrls[$eventName]) || empty($this->webhookUrls[$eventName])) {
            return;
        }

        $jsonPayload = json_encode($payload, JSON_UNESCAPED_SLASHES);

        foreach ($this->webhookUrls[$eventName] as $url) {
            $this->sendRequest($url, $jsonPayload, $eventName);
        }
    }

    /**
     * Envía una única petición de webhook usando cURL.
     *
     * @param string $url La URL de destino.
     * @param string $jsonPayload La carga útil en formato JSON.
     * @param string $eventName El nombre del evento, usado en la cabecera.
     */
    private function sendRequest(string $url, string $jsonPayload, string $eventName): void
    {
        $ch = curl_init($url);

        try {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // Tiempo de espera para conectar
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);      // Tiempo de espera total de la respuesta
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonPayload),
                'User-Agent: PasteX-Pro-Webhook/1.0',
                'X-PasteX-Event: ' . $eventName
            ]);
            
            // NO se verifica la respuesta (fire and forget).
            // En un sistema más robusto, se registraría el código de respuesta
            // y se reintentarían los fallos usando una cola.
            curl_exec($ch);

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode >= 400) {
                 error_log("Webhook Error: Failed to send '{$eventName}' to {$url}. Status: {$httpCode}");
            }

        } catch (Exception $e) {
            error_log("Webhook cURL Exception: " . $e->getMessage());
        } finally {
            curl_close($ch);
        }
    }
}