<?php
// app/core/helpers.php

if (!function_exists('e')) {
    /**
     * HTML エスケープ。
     */
    function e($value): string
    {
        if ($value === null) {
            return '';
        }
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('config')) {
    /**
     * ドット区切りで設定を取得。
     */
    function config(string $key, $default = null)
    {
        $cfg = FORMKUN_CONFIG;
        foreach (explode('.', $key) as $k) {
            if (is_array($cfg) && array_key_exists($k, $cfg)) {
                $cfg = $cfg[$k];
            } else {
                return $default;
            }
        }
        return $cfg;
    }
}

if (!function_exists('base_path')) {
    /**
     * サブディレクトリ運用に対応したベースパス + 任意のパス。
     */
    function base_path(string $path = ''): string
    {
        $base = rtrim((string) config('site.base_path', ''), '/');
        if ($path !== '' && $path[0] !== '/') {
            $path = '/' . $path;
        }
        return $base . $path;
    }
}

if (!function_exists('url')) {
    /**
     * 絶対URLが必要なケース用。base_url が設定されていれば使う。
     */
    function url(string $path = ''): string
    {
        $baseUrl = rtrim((string) config('site.base_url', ''), '/');
        if ($baseUrl !== '') {
            return $baseUrl . base_path($path);
        }
        return base_path($path);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): void
    {
        header('Location: ' . base_path($path));
        exit;
    }
}

if (!function_exists('flash')) {
    /**
     * フラッシュメッセージ取得 or 設定。
     */
    function flash(?string $type = null, ?string $message = null)
    {
        if ($type === null) {
            $messages = $_SESSION['_flash'] ?? [];
            unset($_SESSION['_flash']);
            return $messages;
        }
        $_SESSION['_flash'][] = ['type' => $type, 'message' => $message];
    }
}

if (!function_exists('old')) {
    /**
     * 直前の入力値（バリデーションエラー時の再表示用）。
     */
    function old(string $key, $default = '')
    {
        $old = $_SESSION['_old'] ?? [];
        return $old[$key] ?? $default;
    }
}

if (!function_exists('client_ip')) {
    function client_ip(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        return substr($ip, 0, 45);
    }
}
