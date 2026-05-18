<?php
// アプリ版（npm run release と package.json の version を揃える）
if (!defined('FORMKUN_BOOT')) {
    http_response_code(404);
    exit;
}
return '1.0.0';
