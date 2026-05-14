<?php
// app/bootstrap.php

if (!defined('FORMKUN_BOOT')) {
    http_response_code(404);
    exit;
}

// PHP バージョンチェック
if (PHP_VERSION_ID < 80000) {
    http_response_code(500);
    echo 'PHP 8.0 以上が必要です。';
    exit;
}

// ベースディレクトリ
define('APP_DIR', __DIR__);
define('PUBLIC_DIR', dirname(__DIR__));
define('UPLOADS_DIR', PUBLIC_DIR . DIRECTORY_SEPARATOR . 'uploads');

// 設定読込（config.local.php で上書き可）
$config = require APP_DIR . '/config.php';
define('FORMKUN_CONFIG', $config);

$debug = !empty($config['debug']);
if ($debug) {
    ini_set('display_errors', '1');
    ini_set('html_errors', '1');
} else {
    ini_set('display_errors', '0');
}
ini_set('log_errors', '1');
error_reporting(E_ALL);

// タイムゾーン
date_default_timezone_set($config['site']['timezone'] ?? 'Asia/Tokyo');

// マルチバイト
if (function_exists('mb_internal_encoding')) {
    mb_internal_encoding('UTF-8');
}
if (function_exists('mb_language')) {
    mb_language('Japanese');
}

// ロケール
if (!empty($config['site']['locale'])) {
    @setlocale(LC_ALL, $config['site']['locale']);
}

// オートローダ（PSR-4 風だがフラットな配置）
spl_autoload_register(function ($class) {
    $map = [
        'Core\\'              => APP_DIR . '/core/',
        'Controllers\\'       => APP_DIR . '/controllers/',
        'Controllers\\Admin\\'=> APP_DIR . '/controllers/admin/',
        'Models\\'            => APP_DIR . '/models/',
    ];

    foreach ($map as $prefix => $base) {
        if (strpos($class, $prefix) === 0) {
            $relative = substr($class, strlen($prefix));
            $file = $base . str_replace('\\', '/', $relative) . '.php';
            if (is_file($file)) {
                require $file;
                return;
            }
        }
    }
});

// 共通の安全ヘッダ
if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    if (defined('FORMKUN_AREA') && FORMKUN_AREA === 'admin') {
        header('X-Robots-Tag: noindex, nofollow');
    }
}

// セッション開始
$sessionConfig = $config['session'];
if (session_status() === PHP_SESSION_NONE) {
    // HTTPS 判定（セッション Cookie の Secure 用）
    // X-Forwarded-Proto は既定では信頼しない（任意ヘッダで偽装可能。ローカル・Docker で誤って Secure になり
    // HTTP では Cookie が送られず「ログインできない」症状になる）
    $trustForwarded = !empty($sessionConfig['trust_forwarded_proto']);
    $isHttps = (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off');
    if ($trustForwarded && strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https') {
        $isHttps = true;
    }

    $cs = $sessionConfig['cookie_secure'] ?? null;
    if ($cs === null || $cs === '') {
        $cookieSecure = $isHttps;
    } else {
        $cookieSecure = (bool) $cs;
    }

    session_name($sessionConfig['name']);
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => $cookieSecure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.gc_maxlifetime', (string) $sessionConfig['lifetime']);
    session_start();
}

// グローバル例外ハンドラ（debug 時はスタックトレースを表示）
set_exception_handler(function (\Throwable $e) use ($debug) {
    error_log('[formkun] ' . $e->getMessage() . "\n" . $e->getTraceAsString());
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/html; charset=UTF-8');
    }
    echo '<!doctype html><meta charset="utf-8"><title>500</title>';
    echo '<h1>500 Internal Server Error</h1>';
    if ($debug) {
        echo '<pre style="white-space:pre-wrap;word-break:break-all;background:#f8fafc;padding:12px;border-radius:6px;">';
        echo htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        echo "\n\n" . htmlspecialchars($e->getTraceAsString(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        echo '</pre>';
    } else {
        echo '<p>サーバ内部でエラーが発生しました。時間をおいて再度お試しください。</p>';
    }
    exit;
});

// DB 初期化（必要時に取得）
\Core\Database::configure($config['db']);

// 旧 DB に users.email 等が無い場合の自動補正（1 リクエストにつき 1 回）
\Core\SchemaPatch::ensureLatest();

// ヘルパ関数
require APP_DIR . '/core/helpers.php';
