<!doctype html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title><?= e($title ?? '管理画面') ?> | <?= e(config('site.admin_name')) ?></title>
<link rel="stylesheet" href="<?= e(base_path('/assets/css/admin.css')) ?>">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.min.css">
<script defer src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script defer src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script defer src="<?= e(base_path('/assets/js/admin.js')) ?>"></script>
</head>
<body class="l-adminShell01">
<?php
require_once __DIR__ . '/admin_components.php';
$__nav = admin_relative_path();
$__navDash = ($__nav === '/' || $__nav === '');
$__navForms = str_starts_with($__nav, '/forms') || str_starts_with($__nav, '/submissions') || str_starts_with($__nav, '/files');
$__navUsers = str_starts_with($__nav, '/users');
$__navSettings = str_starts_with($__nav, '/settings');
?>
<div class="l-adminContainer01">
    <header class="c-adminHeader01">
        <div class="wrapper">
            <div class="block is-contents">
                <div class="area is-logo">
                    <a href="<?= e(base_path('/admin/')) ?>"><?= e(config('site.admin_name')) ?></a>
                </div>
                <nav class="area is-nav">
                    <a href="<?= e(base_path('/admin/')) ?>" class="<?= $__navDash ? 'is-active' : '' ?>"<?= $__navDash ? ' aria-current="page"' : '' ?>>ダッシュボード</a>
                    <a href="<?= e(base_path('/admin/forms')) ?>" class="<?= $__navForms ? 'is-active' : '' ?>"<?= $__navForms ? ' aria-current="page"' : '' ?>>フォーム</a>
                    <?php if (\Core\Auth::role() === 'admin'): ?>
                        <a href="<?= e(base_path('/admin/users')) ?>" class="<?= $__navUsers ? 'is-active' : '' ?>"<?= $__navUsers ? ' aria-current="page"' : '' ?>>ユーザー</a>
                    <?php endif; ?>
                    <a href="<?= e(base_path('/admin/settings')) ?>" class="<?= $__navSettings ? 'is-active' : '' ?>"<?= $__navSettings ? ' aria-current="page"' : '' ?>>設定</a>
                </nav>
                <div class="area is-user">
                    <?php if ($u = \Core\Auth::user()): ?>
                        <span class="name"><?= e($u['display_name'] ?? $u['username']) ?>（<?= e($u['role']) ?>）</span>
                        <?php admin_m_btn01_open(); ?>
                            <a class="btn is-danger" href="<?= e(base_path('/admin/logout')) ?>">ログアウト</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <main class="l-adminBody01">
        <div class="wrapper">
            <?= $content ?? '' ?>
        </div>
    </main>
</div>
<div class="c-adminToastStack01" id="admin-toast-stack" aria-live="polite" aria-relevant="additions text">
<?php foreach ((flash() ?? []) as $f): ?>
    <div class="c-adminToast01 is-<?= e($f['type']) ?>" role="status" data-admin-autotoast><?= e($f['message']) ?></div>
<?php endforeach; ?>
</div>
</body>
</html>
