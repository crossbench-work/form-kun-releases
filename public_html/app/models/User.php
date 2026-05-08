<?php
namespace Models;

use Core\Database;

class User
{
    public static function all(): array
    {
        return Database::fetchAll('SELECT id, username, display_name, role, last_login_at, created_at FROM users ORDER BY id ASC');
    }

    public static function find(int $id): ?array
    {
        return Database::fetchOne('SELECT * FROM users WHERE id = ?', [$id]);
    }

    public static function findByUsername(string $username): ?array
    {
        return Database::fetchOne('SELECT * FROM users WHERE username = ?', [$username]);
    }

    public static function count(): int
    {
        $row = Database::fetchOne('SELECT COUNT(*) AS c FROM users');
        return (int) ($row['c'] ?? 0);
    }

    public static function create(string $username, string $password, string $displayName, string $role): int
    {
        $now = date('Y-m-d H:i:s');
        Database::execute(
            'INSERT INTO users (username, password_hash, display_name, role, created_at, updated_at)
             VALUES (?,?,?,?,?,?)',
            [
                $username,
                password_hash($password, PASSWORD_DEFAULT),
                $displayName,
                $role,
                $now,
                $now,
            ]
        );
        return Database::lastInsertId();
    }

    public static function update(int $id, string $displayName, string $role, ?string $newPassword = null): void
    {
        $now = date('Y-m-d H:i:s');
        if ($newPassword !== null && $newPassword !== '') {
            Database::execute(
                'UPDATE users SET display_name = ?, role = ?, password_hash = ?, updated_at = ? WHERE id = ?',
                [$displayName, $role, password_hash($newPassword, PASSWORD_DEFAULT), $now, $id]
            );
        } else {
            Database::execute(
                'UPDATE users SET display_name = ?, role = ?, updated_at = ? WHERE id = ?',
                [$displayName, $role, $now, $id]
            );
        }
    }

    public static function delete(int $id): void
    {
        Database::execute('DELETE FROM users WHERE id = ?', [$id]);
    }

    public static function recordLogin(int $id): void
    {
        Database::execute('UPDATE users SET last_login_at = ? WHERE id = ?', [date('Y-m-d H:i:s'), $id]);
    }
}
