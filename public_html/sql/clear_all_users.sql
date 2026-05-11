-- =================================================================
-- 管理ユーザー・ログイン関連データをすべて削除する（再セットアップ用）
--
-- 実行後はユーザーが 0 件になり、/admin/ にアクセスすると初期セットアップへ進めます。
-- フォームや送信履歴は削除しません。
--
-- password_reset_tokens テーブルは migration_password_reset.sql 適用後に存在します。
-- =================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DELETE FROM login_attempts;
DELETE FROM users;

SET FOREIGN_KEY_CHECKS = 1;
