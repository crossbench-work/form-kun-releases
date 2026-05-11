-- =================================================================
-- 既存データベース向け：パスワード再設定（メール）機能用マイグレーション
-- phpMyAdmin などで 1 回だけ実行してください。
-- =================================================================

SET NAMES utf8mb4;

-- users にメール列（未適用のときだけ実行。既にある場合はエラーになるのでその行をスキップ）
ALTER TABLE users
    ADD COLUMN email VARCHAR(255) DEFAULT NULL AFTER display_name;

ALTER TABLE users
    ADD UNIQUE KEY uk_users_email (email);

CREATE TABLE IF NOT EXISTS password_reset_tokens (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    user_id         INT NOT NULL,
    token_hash      CHAR(64) NOT NULL,
    expires_at      DATETIME NOT NULL,
    created_at      DATETIME NOT NULL,
    UNIQUE KEY uk_token_hash (token_hash),
    KEY idx_user_id (user_id),
    CONSTRAINT fk_pwreset_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
