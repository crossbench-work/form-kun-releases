<?php
// public_html/admin/index.php
// 管理画面のエントリポイント

define('FORMKUN_BOOT', true);
define('FORMKUN_AREA', 'admin');

require __DIR__ . '/../app/bootstrap.php';

$router = new \Core\Router('/admin');

// 認証
$router->get('/login',  fn() => (new \Controllers\Admin\AuthController())->loginForm());
$router->post('/login', fn() => (new \Controllers\Admin\AuthController())->login());
$router->get('/logout', fn() => (new \Controllers\Admin\AuthController())->logout());

// パスワード再設定（メール）
$router->get('/forgot-password',  fn() => (new \Controllers\Admin\AuthController())->forgotForm());
$router->post('/forgot-password', fn() => (new \Controllers\Admin\AuthController())->forgotSubmit());
$router->get('/reset-password',   fn() => (new \Controllers\Admin\AuthController())->resetForm());
$router->post('/reset-password',  fn() => (new \Controllers\Admin\AuthController())->resetSubmit());

// 初期セットアップ（初回管理者作成）
$router->get('/setup',  fn() => (new \Controllers\Admin\SetupController())->show());
$router->post('/setup', fn() => (new \Controllers\Admin\SetupController())->create());

// ダッシュボード
$router->get('/',  fn() => (new \Controllers\Admin\DashboardController())->index());
$router->get('',   fn() => (new \Controllers\Admin\DashboardController())->index());

// 全体設定（サイトは config 参照、フォーム作成上限は DB・管理者のみ更新可）
$router->get('/settings', fn() => (new \Controllers\Admin\SettingsController())->index());
$router->post('/settings', fn() => (new \Controllers\Admin\SettingsController())->update());

$router->get('/settings/update', fn() => (new \Controllers\Admin\SystemUpdateController())->index());
$router->post('/settings/update', fn() => (new \Controllers\Admin\SystemUpdateController())->run());

// フォーム管理
$router->get('/forms',                  fn() => (new \Controllers\Admin\FormController())->index());
$router->get('/forms/new',              fn() => (new \Controllers\Admin\FormController())->createForm());
$router->post('/forms',                 fn() => (new \Controllers\Admin\FormController())->store());
$router->post('/forms/preview-input',   fn() => (new \Controllers\Admin\FormController())->previewInput());
$router->post('/forms/preview-confirm', fn() => (new \Controllers\Admin\FormController())->previewConfirm());
$router->post('/forms/preview-thanks',  fn() => (new \Controllers\Admin\FormController())->previewThanks());
$router->get('/forms/{id}/edit',        fn($p) => (new \Controllers\Admin\FormController())->edit((int)$p['id']));
$router->post('/forms/{id}',            fn($p) => (new \Controllers\Admin\FormController())->update((int)$p['id']));
$router->post('/forms/{id}/delete',     fn($p) => (new \Controllers\Admin\FormController())->destroy((int)$p['id']));

// 送信履歴
$router->get('/forms/{id}/submissions', fn($p) => (new \Controllers\Admin\SubmissionController())->index((int)$p['id']));
$router->get('/submissions/{id}',       fn($p) => (new \Controllers\Admin\SubmissionController())->show((int)$p['id']));
$router->post('/submissions/{id}/delete', fn($p) => (new \Controllers\Admin\SubmissionController())->destroy((int)$p['id']));
$router->get('/files/{id}',             fn($p) => (new \Controllers\Admin\SubmissionController())->download((int)$p['id']));

// ユーザー管理
$router->get('/users',                fn() => (new \Controllers\Admin\UserController())->index());
$router->get('/users/new',            fn() => (new \Controllers\Admin\UserController())->createForm());
$router->post('/users',               fn() => (new \Controllers\Admin\UserController())->store());
$router->get('/users/{id}/edit',      fn($p) => (new \Controllers\Admin\UserController())->edit((int)$p['id']));
$router->post('/users/{id}',          fn($p) => (new \Controllers\Admin\UserController())->update((int)$p['id']));
$router->post('/users/{id}/delete',   fn($p) => (new \Controllers\Admin\UserController())->destroy((int)$p['id']));

$router->dispatch();
