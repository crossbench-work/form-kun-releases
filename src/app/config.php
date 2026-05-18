<?php
// app/config.php
// 直接アクセスを防止
if (!defined('FORMKUN_BOOT')) {
    http_response_code(404);
    exit;
}

$config = [
    // true にすると画面に PHP エラーを表示し、未捕捉例外のスタックを表示（ローカル専用推奨）
    'debug'      => false,

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
        'admin_name' => 'ふぉーむくん',
        // タイムゾーン
        'timezone'   => 'Asia/Tokyo',
        // ロケール
        'locale'     => 'ja_JP.UTF-8',
    ],

    // メール送信設定
    'mail' => [
        // mail() / mb_send_mail() を使う場合は mail、SMTP サーバーへ直接送る場合は smtp
        'transport' => 'mail',
        // From に使うメールアドレス（管理者通知や自動返信の差出人）
        'from_email' => 'noreply@example.com',
        'from_name'  => 'ふぉーむくん',
        'smtp' => [
            'enabled' => false,
            'host' => '127.0.0.1',
            'port' => 1025,
            // '', 'ssl', 'tls' のいずれか
            'secure' => '',
            'username' => '',
            'password' => '',
            'timeout' => 10,
            'ehlo_host' => 'localhost',
            'verify_peer' => true,
            'verify_peer_name' => true,
            'allow_self_signed' => false,
        ],
        // パスワード再設定メール（{{reset_url}} を置換）
        'password_reset_subject' => '[ふぉーむくん] パスワード再設定のご案内',
        'password_reset_body'    => "管理画面へログインするパスワードを再設定するには、次のURLにアクセスしてください（1時間以内）。\n\n{{reset_url}}\n\n心当たりがない場合はこのメールを無視してください。",
    ],

    // 運用上限（管理画面の設定で DB に保存した値があればそちらを優先）
    'limits' => [
        // 作成できるフォーム数の既定上限（1 以上）
        'max_forms' => 50,
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
        // Cookie の Secure フラグ。null のとき実際の HTTPS 接続のみ true（推奨）
        // true/false で固定も可（HTTP のローカルでは false が必要なことがあります）
        'cookie_secure' => null,
        // true のときだけ X-Forwarded-Proto: https を Secure 判定に使う（TLS 終端プロキシの背後用）
        // 既定 false（ヘッダは偽装可能なため。ローカルで誤って Secure Cookie になりログインできなくなるのを防ぐ）
        'trust_forwarded_proto' => false,
    ],

    // アプリ自己更新（本番の値は config.local.php のみ。ZIP には含めない）
    'update' => [
        'enabled' => false,
        'manifest_url' => '',
        'allowed_hosts' => ['raw.githubusercontent.com'],
    ],

    // セキュリティ
    'security' => [
        // 管理画面ログインの連続失敗ロック（同IPからの試行）
        'login_max_attempts'    => 5,
        'login_lockout_seconds' => 600, // 10分
        // パスワード再設定リンクの有効時間（秒）
        'password_reset_ttl_seconds'       => 3600,
        // 「パスワードを忘れた」送信（同一ブラウザセッションあたり・約1時間）
        'password_reset_forgot_max_per_hour' => 5,
    ],
];

// ローカル用上書き（Git に含めない）：このファイルを複製して編集
// env/local/config.local.php または config.local.example.php を参照
$localPath = __DIR__ . '/config.local.php';
if (is_file($localPath)) {
    $local = require $localPath;
    if (is_array($local)) {
        $config = array_replace_recursive($config, $local);
    }
}

return $config;
