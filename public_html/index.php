<?php
// public_html/index.php
// フロント（公開フォーム）側のエントリポイント

define('FORMKUN_BOOT', true);
define('FORMKUN_AREA', 'front');

require __DIR__ . '/app/bootstrap.php';

$router = new \Core\Router();

// フォーム表示
$router->get('/',                fn() => (new \Controllers\HomeController())->index());
$router->get('/f/{slug}',        fn($p) => (new \Controllers\FormController())->show($p['slug']));
$router->post('/f/{slug}/confirm', fn($p) => (new \Controllers\FormController())->confirm($p['slug']));
$router->post('/f/{slug}/submit',  fn($p) => (new \Controllers\FormController())->submit($p['slug']));

$router->dispatch();
