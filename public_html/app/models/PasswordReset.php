<?php
namespace Models;

use Core\Database;

class PasswordReset
{
    public static function deleteExpired(): void
    {
        try {
            Database::execute('DELETE FROM password_reset_tokens WHERE expires_at < NOW()');
        } catch (\Throwable $e) {
            // テーブル未作成時は無視（マイグレーション前）
            error_log('[formkun] password_reset_tokens deleteExpired: ' . $e->getMessage());
        }
    }

    /**
     * 既存トークンを失効させ、新しいトークンを発行する（平文トークンを返す）。
     */
    public static function issueToken(int $userId): string
    {
        self::deleteExpired();
        Database::execute('DELETE FROM password_reset_tokens WHERE user_id = ?', [$userId]);

        $raw = bin2hex(random_bytes(32));
        $hash = hash('sha256', $raw);
        $ttl  = (int) config('security.password_reset_ttl_seconds', 3600);
        $expires = date('Y-m-d H:i:s', time() + max(300, $ttl));

        Database::execute(
            'INSERT INTO password_reset_tokens (user_id, token_hash, expires_at, created_at) VALUES (?,?,?,?)',
            [$userId, $hash, $expires, date('Y-m-d H:i:s')]
        );
        return $raw;
    }

    /**
     * トークンが有効なら user_id を返す（トークンはまだ消さない）。
     */
    public static function findUserIdByToken(string $rawToken): ?int
    {
        self::deleteExpired();
        if (strlen($rawToken) < 32) {
            return null;
        }
        $hash = hash('sha256', $rawToken);
        $row = Database::fetchOne(
            'SELECT user_id FROM password_reset_tokens WHERE token_hash = ? AND expires_at >= NOW()',
            [$hash]
        );
        return $row ? (int) $row['user_id'] : null;
    }

    public static function consumeToken(string $rawToken): void
    {
        $hash = hash('sha256', $rawToken);
        Database::execute('DELETE FROM password_reset_tokens WHERE token_hash = ?', [$hash]);
    }
}
