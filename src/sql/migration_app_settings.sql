-- フォーム作成数上限などアプリ全体のキー値設定
CREATE TABLE IF NOT EXISTS app_settings (
    setting_key   VARCHAR(64) NOT NULL PRIMARY KEY,
    setting_value TEXT NOT NULL,
    created_at    DATETIME NOT NULL,
    updated_at    DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 既に 3 列のみの app_settings がある場合（created_at 不足で INSERT が失敗する環境向け）
-- ALTER TABLE app_settings ADD COLUMN created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER setting_value;
