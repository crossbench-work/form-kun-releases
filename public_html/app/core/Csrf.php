<?php
namespace Core;

class Csrf
{
    private const SESSION_KEY = '_csrf_token';

    public static function token(): string
    {
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::SESSION_KEY];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_token" value="' . e(self::token()) . '">';
    }

    public static function check(?string $token): bool
    {
        $expected = $_SESSION[self::SESSION_KEY] ?? null;
        if (!$expected || !is_string($token) || $token === '') {
            return false;
        }
        return hash_equals($expected, $token);
    }

    /**
     * POST 用の検証。失敗時は 419 で終了。
     */
    public static function verify(): void
    {
        $token = $_POST['_token'] ?? null;
        if (!self::check($token)) {
            http_response_code(419);
            echo '<!doctype html><meta charset="utf-8"><title>419</title>';
            echo '<h1>セッションが切れています</h1>';
            echo '<p>ページを再読み込みしてからもう一度お試しください。</p>';
            exit;
        }
    }

    public static function rotate(): void
    {
        $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
    }
}
