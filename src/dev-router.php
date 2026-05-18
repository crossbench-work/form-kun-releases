<?php
/**
 * ルーティング入口（PHP 組み込みサーバー / nginx / Local 等）
 *
 * - PHP 組み込みサーバー（cli-server）: このファイルを router として指定し、
 *   実ファイルは return false でサーバに静か配信させる。
 * - nginx（Local.app 等）: try_files の最後を /router.php にし、実ファイルは nginx が先に処理。
 *
 * 起動例（リポジトリルートで）:
 *   php -S 127.0.0.1:8080 -t public_html public_html/router.php
 *
 * または scripts/dev-server.sh
 */

declare(strict_types=1);

$root = __DIR__;
$uri  = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');

// PHP 組み込みサーバー限定: 実ファイルはビルトインサーバに任せる
if (PHP_SAPI === 'cli-server' && $uri !== '/' && $uri !== '') {
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
