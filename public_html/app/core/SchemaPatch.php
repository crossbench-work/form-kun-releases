<?php
namespace Core;

/**
 * 旧 DB（schema.sql 追加前に作成されたデータベース）向けの軽量マイグレーション。
 * 初回リクエスト時に不足列・不足テーブルを補います。
 */
class SchemaPatch
{
    private static bool $done = false;

    public static function ensureLatest(): void
    {
        if (self::$done) {
            return;
        }
        self::$done = true;

        try {
            $dbRow = Database::fetchOne('SELECT DATABASE() AS n');
            $schema = (string) ($dbRow['n'] ?? '');
            if ($schema === '') {
                return;
            }

            $hasEmail = Database::fetchOne(
                'SELECT COUNT(*) AS c FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?',
                [$schema, 'users', 'email']
            );
            if ((int) ($hasEmail['c'] ?? 0) === 0) {
                Database::execute(
                    'ALTER TABLE users ADD COLUMN email VARCHAR(255) DEFAULT NULL AFTER display_name'
                );
            }

            self::tryAlterUniqueEmail();

            self::ensureFormFieldsRegexText();

            self::ensureFormFieldsTypeEnumAddress();

            $hasTbl = Database::fetchOne(
                'SELECT COUNT(*) AS c FROM information_schema.TABLES
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?',
                [$schema, 'password_reset_tokens']
            );
            if ((int) ($hasTbl['c'] ?? 0) === 0) {
                Database::execute(
                    'CREATE TABLE password_reset_tokens (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        user_id INT NOT NULL,
                        token_hash CHAR(64) NOT NULL,
                        expires_at DATETIME NOT NULL,
                        created_at DATETIME NOT NULL,
                        UNIQUE KEY uk_token_hash (token_hash),
                        KEY idx_user_id (user_id),
                        CONSTRAINT fk_pwreset_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
                );
            }
        } catch (\Throwable $e) {
            error_log('[formkun] SchemaPatch failed: ' . $e->getMessage());
        }
    }

    private static function tryAlterUniqueEmail(): void
    {
        try {
            Database::execute('ALTER TABLE users ADD UNIQUE KEY uk_users_email (email)');
        } catch (\Throwable $e) {
            $m = $e->getMessage();
            if (stripos($m, 'Duplicate') !== false || stripos($m, '1061') !== false || stripos($m, 'already exists') !== false) {
                return;
            }
            error_log('[formkun] SchemaPatch uk_users_email: ' . $m);
        }
    }

    /**
     * form_fields.regex を複数パターン（JSON）格納用に TEXT へ拡張。
     */
    public static function ensureFormFieldsRegexText(): void
    {
        try {
            $dbRow = Database::fetchOne('SELECT DATABASE() AS n');
            $schema = (string) ($dbRow['n'] ?? '');
            if ($schema === '') {
                return;
            }
            $col = Database::fetchOne(
                'SELECT DATA_TYPE AS t FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?',
                [$schema, 'form_fields', 'regex']
            );
            if (!$col) {
                return;
            }
            $type = strtolower((string) ($col['t'] ?? ''));
            if ($type === 'varchar') {
                Database::execute('ALTER TABLE form_fields MODIFY regex TEXT DEFAULT NULL');
            }
        } catch (\Throwable $e) {
            error_log('[formkun] SchemaPatch form_fields.regex: ' . $e->getMessage());
        }
    }

    /**
     * form_fields.type ENUM に address（住所）を追加。
     */
    public static function ensureFormFieldsTypeEnumAddress(): void
    {
        try {
            $dbRow = Database::fetchOne('SELECT DATABASE() AS n');
            $schema = (string) ($dbRow['n'] ?? '');
            if ($schema === '') {
                return;
            }
            $col = Database::fetchOne(
                'SELECT COLUMN_TYPE AS ct FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?',
                [$schema, 'form_fields', 'type']
            );
            $ct = strtolower((string) ($col['ct'] ?? ''));
            if ($ct === '' || strpos($ct, 'address') !== false) {
                return;
            }
            Database::execute(
                "ALTER TABLE form_fields MODIFY COLUMN type ENUM(
                    'text','textarea','email','tel','number','address',
                    'radio','checkbox','select','date','time','file'
                ) NOT NULL"
            );
        } catch (\Throwable $e) {
            error_log('[formkun] SchemaPatch form_fields.type address: ' . $e->getMessage());
        }
    }
}
