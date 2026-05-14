<?php
namespace Models;

use Core\Database;

class AppSetting
{
    /**
     * @var array<string, string>
     */
    private const MAIL_SETTING_TYPES = [
        'mail.transport' => 'string',
        'mail.from_email' => 'string',
        'mail.from_name' => 'string',
        'mail.smtp.enabled' => 'bool',
        'mail.smtp.host' => 'string',
        'mail.smtp.port' => 'int',
        'mail.smtp.secure' => 'string',
        'mail.smtp.username' => 'string',
        'mail.smtp.password' => 'string',
        'mail.smtp.timeout' => 'int',
        'mail.smtp.ehlo_host' => 'string',
        'mail.smtp.verify_peer' => 'bool',
        'mail.smtp.verify_peer_name' => 'bool',
        'mail.smtp.allow_self_signed' => 'bool',
    ];

    /**
     * @return array<string, mixed>
     */
    public static function loadConfigOverrides(): array
    {
        try {
            $rows = Database::fetchAll(
                'SELECT setting_key, setting_value FROM app_settings WHERE setting_key IN (' .
                implode(', ', array_fill(0, count(self::MAIL_SETTING_TYPES), '?')) .
                ')',
                array_keys(self::MAIL_SETTING_TYPES)
            );
        } catch (\Throwable $e) {
            error_log('[formkun] AppSetting load failed: ' . $e->getMessage());
            return [];
        }

        $config = [];
        foreach ($rows as $row) {
            $key = (string) ($row['setting_key'] ?? '');
            if (!isset(self::MAIL_SETTING_TYPES[$key])) {
                continue;
            }
            self::assignNestedValue(
                $config,
                explode('.', $key),
                self::castValue((string) ($row['setting_value'] ?? ''), self::MAIL_SETTING_TYPES[$key])
            );
        }

        return $config;
    }

    /**
     * @return array<string, mixed>
     */
    public static function currentMailSettings(): array
    {
        return [
            'transport' => (string) config('mail.transport', 'mail'),
            'from_email' => (string) config('mail.from_email', ''),
            'from_name' => (string) config('mail.from_name', ''),
            'smtp_enabled' => !empty(config('mail.smtp.enabled', false)) ? 1 : 0,
            'smtp_host' => (string) config('mail.smtp.host', ''),
            'smtp_port' => (string) config('mail.smtp.port', 25),
            'smtp_secure' => (string) config('mail.smtp.secure', ''),
            'smtp_username' => (string) config('mail.smtp.username', ''),
            'smtp_password' => '',
            'smtp_timeout' => (string) config('mail.smtp.timeout', 10),
            'smtp_ehlo_host' => (string) config('mail.smtp.ehlo_host', ''),
            'smtp_verify_peer' => !empty(config('mail.smtp.verify_peer', true)) ? 1 : 0,
            'smtp_verify_peer_name' => !empty(config('mail.smtp.verify_peer_name', true)) ? 1 : 0,
            'smtp_allow_self_signed' => !empty(config('mail.smtp.allow_self_signed', false)) ? 1 : 0,
            'smtp_password_configured' => trim((string) config('mail.smtp.password', '')) !== '',
            'smtp_clear_password' => 0,
        ];
    }

    /**
     * @param array<string, mixed> $settings
     */
    public static function saveMailSettings(array $settings): void
    {
        $now = date('Y-m-d H:i:s');
        $values = [
            'mail.transport' => (string) ($settings['transport'] ?? 'mail'),
            'mail.from_email' => trim((string) ($settings['from_email'] ?? '')),
            'mail.from_name' => trim((string) ($settings['from_name'] ?? '')),
            'mail.smtp.enabled' => !empty($settings['smtp_enabled']) ? '1' : '0',
            'mail.smtp.host' => trim((string) ($settings['smtp_host'] ?? '')),
            'mail.smtp.port' => (string) ($settings['smtp_port'] ?? '25'),
            'mail.smtp.secure' => trim((string) ($settings['smtp_secure'] ?? '')),
            'mail.smtp.username' => trim((string) ($settings['smtp_username'] ?? '')),
            'mail.smtp.timeout' => (string) ($settings['smtp_timeout'] ?? '10'),
            'mail.smtp.ehlo_host' => trim((string) ($settings['smtp_ehlo_host'] ?? '')),
            'mail.smtp.verify_peer' => !empty($settings['smtp_verify_peer']) ? '1' : '0',
            'mail.smtp.verify_peer_name' => !empty($settings['smtp_verify_peer_name']) ? '1' : '0',
            'mail.smtp.allow_self_signed' => !empty($settings['smtp_allow_self_signed']) ? '1' : '0',
        ];

        $shouldUpdatePassword = array_key_exists('smtp_password', $settings)
            && ((string) $settings['smtp_password'] !== '' || !empty($settings['smtp_clear_password']));

        if ($shouldUpdatePassword) {
            $values['mail.smtp.password'] = !empty($settings['smtp_clear_password'])
                ? ''
                : (string) $settings['smtp_password'];
        }

        Database::transaction(function () use ($values, $now): void {
            foreach ($values as $key => $value) {
                Database::execute(
                    'INSERT INTO app_settings (setting_key, setting_value, created_at, updated_at)
                     VALUES (?, ?, ?, ?)
                     ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = VALUES(updated_at)',
                    [$key, $value, $now, $now]
                );
            }
        });
    }

    /**
     * @param array<string, mixed> $target
     * @param array<int, string> $segments
     * @param mixed $value
     */
    private static function assignNestedValue(array &$target, array $segments, $value): void
    {
        $segment = array_shift($segments);
        if ($segment === null || $segment === '') {
            return;
        }
        if ($segments === []) {
            $target[$segment] = $value;
            return;
        }
        if (!isset($target[$segment]) || !is_array($target[$segment])) {
            $target[$segment] = [];
        }
        self::assignNestedValue($target[$segment], $segments, $value);
    }

    /**
     * @return bool|int|string
     */
    private static function castValue(string $value, string $type)
    {
        if ($type === 'bool') {
            return $value === '1';
        }
        if ($type === 'int') {
            return (int) $value;
        }

        return $value;
    }
}
