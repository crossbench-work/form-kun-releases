-- =================================================================
-- フォーム管理システム データベーススキーマ
-- MySQL 5.7+ / MariaDB 10.2+
-- 文字コード: utf8mb4
-- =================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 管理ユーザー
CREATE TABLE IF NOT EXISTS users (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    username        VARCHAR(64) UNIQUE NOT NULL,
    password_hash   VARCHAR(255) NOT NULL,
    display_name    VARCHAR(100) DEFAULT NULL,
    email           VARCHAR(255) DEFAULT NULL,
    role            ENUM('admin','editor') NOT NULL DEFAULT 'editor',
    last_login_at   DATETIME DEFAULT NULL,
    created_at      DATETIME NOT NULL,
    updated_at      DATETIME NOT NULL,
    UNIQUE KEY uk_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- パスワード再設定トークン（メールリンク用）
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

-- フォーム本体
CREATE TABLE IF NOT EXISTS forms (
    id                      INT AUTO_INCREMENT PRIMARY KEY,
    slug                    VARCHAR(64) UNIQUE NOT NULL,
    title                   VARCHAR(200) NOT NULL,
    input_page_title        VARCHAR(200) DEFAULT NULL,
    description             TEXT DEFAULT NULL,
    show_title              TINYINT(1) NOT NULL DEFAULT 1,
    show_description        TINYINT(1) NOT NULL DEFAULT 1,
    confirm_page_title      VARCHAR(200) DEFAULT NULL,
    confirm_button_text     VARCHAR(200) DEFAULT NULL,
    submit_button_text      VARCHAR(200) DEFAULT NULL,
    back_button_text        VARCHAR(200) DEFAULT NULL,
    confirm_message         TEXT DEFAULT NULL,
    thanks_page_title       VARCHAR(200) DEFAULT NULL,
    thanks_message          TEXT DEFAULT NULL,
    thanks_button_text      VARCHAR(200) DEFAULT NULL,
    thanks_button_url       VARCHAR(500) DEFAULT NULL,
    admin_emails            VARCHAR(500) DEFAULT NULL,
    mail_subject_admin      VARCHAR(200) DEFAULT NULL,
    mail_template_admin     TEXT DEFAULT NULL,
    reply_enabled           TINYINT(1) NOT NULL DEFAULT 0,
    reply_from_field_key    VARCHAR(64) DEFAULT NULL,
    reply_subject           VARCHAR(200) DEFAULT NULL,
    reply_template          TEXT DEFAULT NULL,
    captcha_enabled         TINYINT(1) NOT NULL DEFAULT 1,
    status                  ENUM('active','draft','archived') NOT NULL DEFAULT 'draft',
    created_at              DATETIME NOT NULL,
    updated_at              DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- フォームの項目
CREATE TABLE IF NOT EXISTS form_fields (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    form_id         INT NOT NULL,
    sort_order      INT NOT NULL DEFAULT 0,
    field_key       VARCHAR(64) NOT NULL,
    label           VARCHAR(200) NOT NULL,
    type            ENUM(
        'text','textarea','email','tel','number','address','text_pair','text_multi',
        'radio','checkbox','select','date','time','file'
    ) NOT NULL,
    required        TINYINT(1) NOT NULL DEFAULT 0,
    max_length      INT DEFAULT NULL,
    regex           TEXT DEFAULT NULL,
    help_text       VARCHAR(500) DEFAULT NULL,
    placeholder     VARCHAR(255) DEFAULT NULL,
    options_json    TEXT DEFAULT NULL,
    UNIQUE KEY uk_form_field (form_id, field_key),
    KEY idx_form_sort (form_id, sort_order),
    CONSTRAINT fk_form_fields_form FOREIGN KEY (form_id) REFERENCES forms(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 送信履歴
CREATE TABLE IF NOT EXISTS submissions (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    form_id         INT NOT NULL,
    ip              VARCHAR(45) DEFAULT NULL,
    user_agent      VARCHAR(255) DEFAULT NULL,
    submitted_at    DATETIME NOT NULL,
    KEY idx_form_submitted (form_id, submitted_at),
    CONSTRAINT fk_submissions_form FOREIGN KEY (form_id) REFERENCES forms(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 送信内容（縦持ち）
CREATE TABLE IF NOT EXISTS submission_values (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    submission_id   INT NOT NULL,
    field_key       VARCHAR(64) NOT NULL,
    value           MEDIUMTEXT,
    KEY idx_sub_key (submission_id, field_key),
    CONSTRAINT fk_subval_submission FOREIGN KEY (submission_id) REFERENCES submissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- アップロードファイル
CREATE TABLE IF NOT EXISTS submission_files (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    submission_id   INT NOT NULL,
    field_key       VARCHAR(64) NOT NULL,
    original_name   VARCHAR(255) NOT NULL,
    stored_path     VARCHAR(500) NOT NULL,
    mime_type       VARCHAR(100) DEFAULT NULL,
    size            INT DEFAULT NULL,
    KEY idx_subfile_submission (submission_id),
    CONSTRAINT fk_subfile_submission FOREIGN KEY (submission_id) REFERENCES submissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ログイン試行ログ（ブルートフォース対策）
CREATE TABLE IF NOT EXISTS login_attempts (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    ip              VARCHAR(45) NOT NULL,
    username        VARCHAR(64) DEFAULT NULL,
    success         TINYINT(1) NOT NULL DEFAULT 0,
    attempted_at    DATETIME NOT NULL,
    KEY idx_ip_time (ip, attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
