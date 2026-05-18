<?php
namespace Core;

class Auth
{
    private const SESSION_USER = '_auth_user';

    public static function check(): bool
    {
        return isset($_SESSION[self::SESSION_USER]);
    }

    public static function user(): ?array
    {
        return $_SESSION[self::SESSION_USER] ?? null;
    }

    public static function id(): ?int
    {
        $user = self::user();
        return $user['id'] ?? null;
    }

    public static function role(): ?string
    {
        $user = self::user();
        return $user['role'] ?? null;
    }

    public static function login(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION[self::SESSION_USER] = [
            'id'           => (int) $user['id'],
            'username'     => (string) $user['username'],
            'display_name' => (string) ($user['display_name'] ?? $user['username']),
            'role'         => (string) $user['role'],
        ];
    }

    public static function logout(): void
    {
        unset($_SESSION[self::SESSION_USER]);
        session_regenerate_id(true);
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            $_SESSION['_intended'] = $_SERVER['REQUEST_URI'] ?? null;
            redirect('/admin/login');
        }
    }

    public static function requireRole(string $role): void
    {
        self::requireLogin();
        if (self::role() !== $role) {
            http_response_code(403);
            echo '<!doctype html><meta charset="utf-8"><title>403</title>';
            echo '<h1>403 Forbidden</h1><p>この操作を行う権限がありません。</p>';
            exit;
        }
    }

    public static function recordAttempt(string $ip, ?string $username, bool $success): void
    {
        try {
            Database::execute(
                'INSERT INTO login_attempts (ip, username, success, attempted_at) VALUES (?, ?, ?, ?)',
                [$ip, $username, $success ? 1 : 0, date('Y-m-d H:i:s')]
            );
        } catch (\Throwable $e) {
            error_log('[formkun] login_attempts insert failed: ' . $e->getMessage());
        }
    }

    public static function isLockedOut(string $ip): bool
    {
        $max     = (int) config('security.login_max_attempts', 5);
        $window  = (int) config('security.login_lockout_seconds', 600);
        $since   = date('Y-m-d H:i:s', time() - $window);

        $row = Database::fetchOne(
            'SELECT COUNT(*) AS c FROM login_attempts WHERE ip = ? AND success = 0 AND attempted_at >= ?',
            [$ip, $since]
        );
        return ((int) ($row['c'] ?? 0)) >= $max;
    }
}
