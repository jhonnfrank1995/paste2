<?php

declare(strict_types=1);

namespace App\Services;

use Exception;

/**
 * Servicio para operaciones de encriptación y desencriptación simétrica.
 * Utiliza AES-256-GCM, un estándar moderno y seguro.
 */
class CryptoService
{
    private const ALGORITHM = 'aes-256-gcm';

    private string $serverKey;

    public function __construct()
    {
        $key = $_ENV['ENCRYPTION_KEY'] ?? '';
        if (empty($key) || !preg_match('/^base64:/', $key)) {
            throw new Exception('ENCRYPTION_KEY is not set or invalid in the .env file. It must be a base64 encoded 32-byte string.');
        }
        // Decodifica la clave desde base64
        $this->serverKey = base64_decode(substr($key, 7));
        if (strlen($this->serverKey) !== 32) {
             throw new Exception('Decoded ENCRYPTION_KEY must be exactly 32 bytes.');
        }
    }

    /**
     * Encripta datos usando la clave del servidor (AES-256-GCM).
     * Devuelve un array con el texto cifrado, el IV (vector de inicialización) y la etiqueta de autenticación.
     *
     * @param string $plaintext El texto a encriptar.
     * @return array ['ciphertext' => string, 'iv' => string, 'tag' => string] (todos codificados en base64)
     * @throws Exception si la encriptación falla.
     */
    public function encrypt(string $plaintext): array
    {
        $iv = random_bytes(openssl_cipher_iv_length(self::ALGORITHM));
        $tag = ''; // El tag es generado y pasado por referencia en modo GCM

        $ciphertext = openssl_encrypt(
            $plaintext,
            self::ALGORITHM,
            $this->serverKey,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        if ($ciphertext === false) {
            throw new Exception('Encryption failed.');
        }

        return [
            'ciphertext' => base64_encode($ciphertext),
            'iv' => base64_encode($iv),
            'tag' => base64_encode($tag),
        ];
    }

    /**
     * Desencripta datos cifrados con la clave del servidor.
     *
     * @param string $ciphertextB64 El texto cifrado en base64.
     * @param string $ivB64 El IV en base64.
     * @param string $tagB64 La etiqueta de autenticación en base64.
     * @return string|null El texto original, o null si la desencriptación falla (ej. tag inválido).
     */
    public function decrypt(string $ciphertextB64, string $ivB64, string $tagB64): ?string
    {
        $ciphertext = base64_decode($ciphertextB64, true);
        $iv = base64_decode($ivB64, true);
        $tag = base64_decode($tagB64, true);

        if ($ciphertext === false || $iv === false || $tag === false) {
            return null; // Datos de entrada malformados
        }

        $plaintext = openssl_decrypt(
            $ciphertext,
            self::ALGORITHM,
            $this->serverKey,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );
        
        return $plaintext === false ? null : $plaintext;
    }
}