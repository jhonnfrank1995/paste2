<?php

declare(strict_types=1);

namespace App\Services;

use Exception;

/**
 * Servicio para la gestión de subida y almacenamiento de archivos.
 */
class StorageService
{
    private string $uploadPath;
    private array $allowedMimeTypes;
    private int $maxFileSize;

    public function __construct()
    {
        $this->uploadPath = __DIR__ . '/../../storage/app/uploads';
        
        // TODO: Mover estas configuraciones a un archivo config/storage.php
        $this->allowedMimeTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'text/plain' => 'txt',
            'application/json' => 'json',
        ];
        $this->maxFileSize = 2 * 1024 * 1024; // 2 MB
    }

    /**
     * Procesa la subida de un archivo desde la superglobal $_FILES.
     *
     * @param array $file El array de un archivo de $_FILES (ej. $_FILES['attachment']).
     * @return array ['status' => 'success'|'error', 'message' => string, 'path' => string|null]
     */
    public function upload(array $file): array
    {
        try {
            if ($file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception($this->uploadErrorMessage($file['error']));
            }
            if ($file['size'] > $this->maxFileSize) {
                throw new Exception('File size exceeds the maximum limit of ' . ($this->maxFileSize / 1024 / 1024) . ' MB.');
            }
            
            // Validar el tipo de archivo usando finfo para más seguridad
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file['tmp_name']);
            
            if (!array_key_exists($mimeType, $this->allowedMimeTypes)) {
                throw new Exception('Invalid file type. Allowed types are: ' . implode(', ', array_keys($this->allowedMimeTypes)));
            }

            // Generar un nombre de archivo único para evitar colisiones y ocultar el nombre original
            $extension = $this->allowedMimeTypes[$mimeType];
            $fileName = bin2hex(random_bytes(16)) . '.' . $extension;
            $destinationPath = $this->uploadPath . '/' . $fileName;

            if (!move_uploaded_file($file['tmp_name'], $destinationPath)) {
                throw new Exception('Failed to move uploaded file.');
            }

            return ['status' => 'success', 'message' => 'File uploaded successfully.', 'path' => 'uploads/' . $fileName];
            
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Convierte los códigos de error de subida de PHP a mensajes legibles.
     */
    private function uploadErrorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'File is too large.',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
            default => 'Unknown upload error.',
        };
    }
}