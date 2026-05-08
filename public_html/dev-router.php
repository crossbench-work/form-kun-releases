<?php
/**
 * PHP 組み込み Web サーバー用ルーター（ローカル開発向け）
 *
 * Apache の mod_rewrite が無い環境でも、本番と同様にパスをルーティングします。
 *
 * 起動例（リポジトリルートで）:
 *   php -S 127.0.0.1:8080 -t public_html public_html/dev-router.php
 *
 * または scripts/dev-server.sh
 */

declare(strict_types=1);

$root = __DIR__;
$uri  = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');

// 静的ファイルが実体としてあればそのまま配信
if ($uri !== '/' && $uri !== '') {
    $path = $root . str_replace('/', DIRECTORY_SEPARATOR, $uri);
    if (is_file($path)) {
        return false;
    }
}

// 機密ディレクトリへの直接アクセスを遮断（本番の .htaccess に相当）
if (preg_match('#^/(app|sql)(/|$)#', $uri)) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Forbidden';
    return true;
}

// アップロード領域は直リンクさせない（開発時も本番相当）
if (preg_match('#^/uploads/#', $uri)) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Forbidden';
    return true;
}

if (preg_match('#^/admin#', $uri)) {
    chdir($root . DIRECTORY_SEPARATOR . 'admin');
    require $root . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'index.php';
    return true;
}

chdir($root);
require $root . DIRECTORY_SEPARATOR . 'index.php';
return true;
