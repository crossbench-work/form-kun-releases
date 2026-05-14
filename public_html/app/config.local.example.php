<?php
/**
 * ローカル開発用設定の例。
 *
 * 使い方:
 *   cp config.local.example.php config.local.php
 *   （MySQL のユーザー・パスワードやポートを自分の環境に合わせる）
 *
 * config.local.php は .gitignore 済みです。
 *
 * このファイルを読み込むには WEB から bootstrap が読み込むだけなので、
 * config.local.php 側でも define は不要です。
 */
return [
    'debug' => true,
    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'name' => 'formkun',
        'user' => 'root',
        'password' => 'secret',
    ],
    'site' => [
        // PHP ビルトインサーバーで開く URL と合わせる（ポートは環境に合わせて変更）
        'base_url'  => 'http://127.0.0.1:8080',
        'base_path' => '',
    ],
    'mail' => [
        // Mailpit / MailHog などローカル SMTP を使う場合の例
        'transport' => 'smtp',
        'smtp' => [
            'enabled' => true,
            'host' => '127.0.0.1',
            'port' => 1025,
            'secure' => '',
            'username' => '',
            'password' => '',
            'timeout' => 10,
            'ehlo_host' => 'localhost',
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ],
    ],
];
