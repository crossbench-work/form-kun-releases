<?php
/**
 * Docker（docker-compose）用の設定例。
 *
 * コンテナ内から DB に繋ぐときホスト名はサービス名の「db」です。
 *
 *   cp config.docker.example.php config.local.php
 *
 * ポートやパスワードを docker-compose.yml / .env と揃えてください。
 */
return [
    'debug' => true,
    'db' => [
        'host'     => 'db',
        'port'     => 3306,
        'name'     => 'formkun',
        'user'     => 'formkun',
        'password' => 'formkun',
    ],
    'site' => [
        // ホストのブラウザからアクセスする URL（compose の HOST_WEB_PORT と一致）
        'base_url'  => 'http://localhost:8080',
        'base_path' => '',
    ],
];
