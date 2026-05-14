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
<div class="l-adminContainer01">
    <header class="c-adminHeader01">
        <div class="wrapper">
            <div class="block is-contents">
                <div class="area is-logo">
                    <a href="<?= e(base_path('/admin/')) ?>"><?= e(config('site.admin_name')) ?></a>
                </div>
                <nav class="area is-nav">
                    <a href="<?= e(base_path('/admin/')) ?>">ダッシュボード</a>
                    <a href="<?= e(base_path('/admin/forms')) ?>">フォーム</a>
                    <?php if (\Core\Auth::role() === 'admin'): ?>
                        <a href="<?= e(base_path('/admin/users')) ?>">ユーザー</a>
                    <?php endif; ?>
                </nav>
                <div class="area is-user">
                    <?php if ($u = \Core\Auth::user()): ?>
                        <span class="name"><?= e($u['display_name'] ?? $u['username']) ?>（<?= e($u['role']) ?>）</span>
                        <a class="link is-logout" href="<?= e(base_path('/admin/logout')) ?>">ログアウト</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <main class="l-adminBody01">
        <div class="wrapper">
            <?php foreach ((flash() ?? []) as $f): ?>
                <div class="m-alert01 is-<?= e($f['type']) ?>"><?= e($f['message']) ?></div>
            <?php endforeach; ?>
            <?= $content ?? '' ?>
        </div>
    </main>
</div>
</body>
</html>
