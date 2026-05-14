<?php
/**
 * サーバー（STG / 本番）用の上書き例。
 *
 * 手順:
 *   1. このファイルをコピーして同じフォルダに `config.local.php` として保存する
 *   2. 下記の CHANGE_ME / 空欄をサーバー環境に合わせて埋める
 *   3. FileZilla でサーバーの `public_html/app/config.local.php` にアップロードする
 *
 * `config.local.php` は Git に含めないでください（.gitignore 済み）。
 */
return [
    'debug' => false,

    'db' => [
        'host' => 'localhost',
        'port' => 3306,
        'name' => 'crossbench01_formkun',
        'user' => 'CHANGE_ME',
        'password' => 'CHANGE_ME',
    ],

    'site' => [
        // 末尾スラッシュなし。例: https://example.com
        'base_url' => 'https://CHANGE_ME.example.com',
        // ドキュメントルート直下なら ''。サブディレクトリ運用なら '/subdir'
        'base_path' => '',
    ],

    // 必要ならメール差出人や SMTP を上書き
    // 'mail' => [
    //     'from_email' => 'noreply@example.com',
    // ],
];
