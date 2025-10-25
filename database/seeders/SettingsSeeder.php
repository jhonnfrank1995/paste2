<?php

namespace Database\Seeders;

use App\Core\DB;

/**
 * Seeder para rellenar la tabla de configuraciones con valores por defecto.
 */
class SettingsSeeder
{
    /**
     * Ejecuta el seeder.
     */
    public function run(): void
    {
        $defaultSettings = [
            'site_name' => 'PasteX Pro',
            'site_logo' => '', // URL a un logo, o vacío para usar el nombre del sitio
            'default_theme' => 'light',
            'allow_guest_pastes' => '1',
            'max_paste_size_kb' => '2048',
            'default_expiration' => '1d',
            'max_expiration' => 'never',
            'allow_attachments' => '0',
            'max_attachment_size_kb' => '1024',
            'enable_comments' => '1',
            'require_captcha_for_guests' => '0',
            'enable_user_registration' => '1',
            'webhooks_enabled' => '0',
            'webhooks_urls' => '{}', // JSON vacío
            'tos_content' => 'Please add your Terms of Service here.',
            'privacy_policy_content' => 'Please add your Privacy Policy here.',
        ];

        $pdo = DB::getInstance();
        
        // Usar INSERT IGNORE para no sobreescribir configuraciones que ya puedan existir.
        $stmt = $pdo->prepare("INSERT IGNORE INTO settings (`key`, `value`) VALUES (:key, :value)");

        foreach ($defaultSettings as $key => $value) {
            $stmt->execute([':key' => $key, ':value' => $value]);
        }
    }
}