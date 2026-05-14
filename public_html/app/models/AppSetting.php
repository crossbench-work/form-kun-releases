<?php
namespace Models;

use Core\Database;

class AppSetting
{
    public const KEY_MAX_FORMS = 'max_forms';

    public static function get(string $key): ?string
    {
        $row = Database::fetchOne(
            'SELECT setting_value FROM app_settings WHERE setting_key = ?',
            [$key]
        );
        if ($row === null) {
            return null;
        }
        return (string) ($row['setting_value'] ?? '');
    }

    public static function set(string $key, string $value): void
    {
        $now = date('Y-m-d H:i:s');
        Database::execute(
            'INSERT INTO app_settings (setting_key, setting_value, updated_at) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = VALUES(updated_at)',
            [$key, $value, $now]
        );
    }
}
