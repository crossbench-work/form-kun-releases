<?php
// app/config.php
// 直接アクセスを防止
if (!defined('FORMKUN_BOOT')) {
    http_response_code(404);
    exit;
}

return [
    // データベース接続情報
    'db' => [
        'host'     => 'localhost',
        'port'     => 3306,
        'name'     => 'formkun',
        'user'     => 'formkun',
        'password' => 'change-me',
        'charset'  => 'utf8mb4',
    ],

    // サイト基本設定
    'site' => [
        // 公開URL（末尾スラッシュなし）。例: https://example.com
        'base_url'   => '',
        // サブディレクトリ運用時はここに（例: '/formkun'）。ドキュメントルート直下なら ''
        'base_path'  => '',
        // 管理画面のサイト名表示
        'admin_name' => 'フォーム管理システム',
        // タイムゾーン
        'timezone'   => 'Asia/Tokyo',
        // ロケール
        'locale'     => 'ja_JP.UTF-8',
    ],

    // メール送信設定
    'mail' => [
        // From に使うメールアドレス（管理者通知や自動返信の差出人）
        'from_email' => 'noreply@example.com',
        'from_name'  => 'フォーム管理システム',
    ],

    // ファイルアップロード設定
    'upload' => [
        // bytes 単位の上限（既定 5MB）
        'max_size' => 5 * 1024 * 1024,
        // 許可拡張子（小文字）
        'allowed_extensions' => [
            'jpg', 'jpeg', 'png', 'gif', 'webp',
            'pdf', 'txt', 'csv',
            'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
            'zip',
        ],
    ],

    // セッション設定
    'session' => [
        'name'     => 'FORMKUNSESSID',
        'lifetime' => 60 * 60 * 4, // 4時間
    ],

    // セキュリティ
    'security' => [
        // 管理画面ログインの連続失敗ロック（同IPからの試行）
        'login_max_attempts'    => 5,
        'login_lockout_seconds' => 600, // 10分
    ],
];
