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

            self::ensureFormFieldsTypeEnumTextPair();

            self::ensureFormFieldsTypeEnumTextMulti();

            self::ensureFormsDisplayFlags();

            self::ensureFormsPageTitles();

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

    /**
     * form_fields.type ENUM に text_pair（1行テキスト2入力）を追加。
     */
    public static function ensureFormFieldsTypeEnumTextPair(): void
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
            if ($ct === '' || strpos($ct, 'text_pair') !== false) {
                return;
            }
            Database::execute(
                "ALTER TABLE form_fields MODIFY COLUMN type ENUM(
                    'text','textarea','email','tel','number','address','text_pair',
                    'radio','checkbox','select','date','time','file'
                ) NOT NULL"
            );
        } catch (\Throwable $e) {
            error_log('[formkun] SchemaPatch form_fields.type text_pair: ' . $e->getMessage());
        }
    }

    /**
     * form_fields.type ENUM に text_multi（1行テキスト複数入力）を追加。
     */
    public static function ensureFormFieldsTypeEnumTextMulti(): void
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
            if ($ct === '' || strpos($ct, 'text_multi') !== false) {
                return;
            }
            Database::execute(
                "ALTER TABLE form_fields MODIFY COLUMN type ENUM(
                    'text','textarea','email','tel','number','address','text_pair','text_multi',
                    'radio','checkbox','select','date','time','file'
                ) NOT NULL"
            );
        } catch (\Throwable $e) {
            error_log('[formkun] SchemaPatch form_fields.type text_multi: ' . $e->getMessage());
        }
    }

    /**
     * forms に公開フォーム側のタイトル・説明文表示フラグを追加。
     */
    public static function ensureFormsDisplayFlags(): void
    {
        try {
            $dbRow = Database::fetchOne('SELECT DATABASE() AS n');
            $schema = (string) ($dbRow['n'] ?? '');
            if ($schema === '') {
                return;
            }

            $hasShowTitle = Database::fetchOne(
                'SELECT COUNT(*) AS c FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?',
                [$schema, 'forms', 'show_title']
            );
            if ((int) ($hasShowTitle['c'] ?? 0) === 0) {
                Database::execute(
                    'ALTER TABLE forms ADD COLUMN show_title TINYINT(1) NOT NULL DEFAULT 1 AFTER description'
                );
            }

            $hasShowDescription = Database::fetchOne(
                'SELECT COUNT(*) AS c FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?',
                [$schema, 'forms', 'show_description']
            );
            if ((int) ($hasShowDescription['c'] ?? 0) === 0) {
                Database::execute(
                    'ALTER TABLE forms ADD COLUMN show_description TINYINT(1) NOT NULL DEFAULT 1 AFTER show_title'
                );
            }

            $hasConfirmButtonText = Database::fetchOne(
                'SELECT COUNT(*) AS c FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?',
                [$schema, 'forms', 'confirm_button_text']
            );
            if ((int) ($hasConfirmButtonText['c'] ?? 0) === 0) {
                Database::execute(
                    'ALTER TABLE forms ADD COLUMN confirm_button_text VARCHAR(200) DEFAULT NULL AFTER show_description'
                );
            }

            $hasSubmitButtonText = Database::fetchOne(
                'SELECT COUNT(*) AS c FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?',
                [$schema, 'forms', 'submit_button_text']
            );
            if ((int) ($hasSubmitButtonText['c'] ?? 0) === 0) {
                Database::execute(
                    'ALTER TABLE forms ADD COLUMN submit_button_text VARCHAR(200) DEFAULT NULL AFTER confirm_button_text'
                );
            }

            $hasBackButtonText = Database::fetchOne(
                'SELECT COUNT(*) AS c FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?',
                [$schema, 'forms', 'back_button_text']
            );
            if ((int) ($hasBackButtonText['c'] ?? 0) === 0) {
                Database::execute(
                    'ALTER TABLE forms ADD COLUMN back_button_text VARCHAR(200) DEFAULT NULL AFTER submit_button_text'
                );
            }

            $hasConfirmMessage = Database::fetchOne(
                'SELECT COUNT(*) AS c FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?',
                [$schema, 'forms', 'confirm_message']
            );
            if ((int) ($hasConfirmMessage['c'] ?? 0) === 0) {
                Database::execute(
                    'ALTER TABLE forms ADD COLUMN confirm_message TEXT DEFAULT NULL AFTER back_button_text'
                );
            }

            $hasThanksButtonText = Database::fetchOne(
                'SELECT COUNT(*) AS c FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?',
                [$schema, 'forms', 'thanks_button_text']
            );
            if ((int) ($hasThanksButtonText['c'] ?? 0) === 0) {
                Database::execute(
                    'ALTER TABLE forms ADD COLUMN thanks_button_text VARCHAR(200) DEFAULT NULL AFTER thanks_message'
                );
            }

            $hasThanksButtonUrl = Database::fetchOne(
                'SELECT COUNT(*) AS c FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?',
                [$schema, 'forms', 'thanks_button_url']
            );
            if ((int) ($hasThanksButtonUrl['c'] ?? 0) === 0) {
                Database::execute(
                    'ALTER TABLE forms ADD COLUMN thanks_button_url VARCHAR(500) DEFAULT NULL AFTER thanks_button_text'
                );
            }
        } catch (\Throwable $e) {
            error_log('[formkun] SchemaPatch forms display flags: ' . $e->getMessage());
        }
    }

    /**
     * forms に入力 / 確認 / 完了画面ごとのタイトル列を追加。
     */
    public static function ensureFormsPageTitles(): void
    {
        try {
            $dbRow = Database::fetchOne('SELECT DATABASE() AS n');
            $schema = (string) ($dbRow['n'] ?? '');
            if ($schema === '') {
                return;
            }

            $hasInputPageTitle = Database::fetchOne(
                'SELECT COUNT(*) AS c FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?',
                [$schema, 'forms', 'input_page_title']
            );
            if ((int) ($hasInputPageTitle['c'] ?? 0) === 0) {
                Database::execute(
                    'ALTER TABLE forms ADD COLUMN input_page_title VARCHAR(200) DEFAULT NULL AFTER title'
                );
            }

            $hasConfirmPageTitle = Database::fetchOne(
                'SELECT COUNT(*) AS c FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?',
                [$schema, 'forms', 'confirm_page_title']
            );
            if ((int) ($hasConfirmPageTitle['c'] ?? 0) === 0) {
                Database::execute(
                    'ALTER TABLE forms ADD COLUMN confirm_page_title VARCHAR(200) DEFAULT NULL AFTER show_description'
                );
            }

            $hasThanksPageTitle = Database::fetchOne(
                'SELECT COUNT(*) AS c FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?',
                [$schema, 'forms', 'thanks_page_title']
            );
            if ((int) ($hasThanksPageTitle['c'] ?? 0) === 0) {
                Database::execute(
                    'ALTER TABLE forms ADD COLUMN thanks_page_title VARCHAR(200) DEFAULT NULL AFTER confirm_message'
                );
            }

            Database::execute(
                "UPDATE forms
                    SET input_page_title = title
                  WHERE (input_page_title IS NULL OR input_page_title = '')
                    AND title IS NOT NULL AND title <> ''"
            );
            Database::execute(
                "UPDATE forms
                    SET confirm_page_title = '入力内容のご確認'
                  WHERE confirm_page_title IS NULL OR confirm_page_title = ''"
            );
            Database::execute(
                "UPDATE forms
                    SET thanks_page_title = '送信が完了しました'
                  WHERE thanks_page_title IS NULL OR thanks_page_title = ''"
            );
        } catch (\Throwable $e) {
            error_log('[formkun] SchemaPatch forms page titles: ' . $e->getMessage());
        }
    }
}
