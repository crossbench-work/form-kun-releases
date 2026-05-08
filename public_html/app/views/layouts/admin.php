<!doctype html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title><?= e($title ?? '管理画面') ?> | <?= e(config('site.admin_name')) ?></title>
<link rel="stylesheet" href="<?= e(base_path('/assets/css/admin.css')) ?>">
<script defer src="<?= e(base_path('/assets/js/admin.js')) ?>"></script>
</head>
<body class="admin">
<header class="admin-header">
    <div class="admin-brand">
        <a href="<?= e(base_path('/admin/')) ?>"><?= e(config('site.admin_name')) ?></a>
    </div>
    <nav class="admin-nav">
        <a href="<?= e(base_path('/admin/')) ?>">ダッシュボード</a>
        <a href="<?= e(base_path('/admin/forms')) ?>">フォーム</a>
        <?php if (\Core\Auth::role() === 'admin'): ?>
            <a href="<?= e(base_path('/admin/users')) ?>">ユーザー</a>
        <?php endif; ?>
    </nav>
    <div class="admin-user">
        <?php if ($u = \Core\Auth::user()): ?>
            <span class="user-name"><?= e($u['display_name'] ?? $u['username']) ?>（<?= e($u['role']) ?>）</span>
            <a class="logout" href="<?= e(base_path('/admin/logout')) ?>">ログアウト</a>
        <?php endif; ?>
    </div>
</header>

<main class="admin-main">
    <?php foreach ((flash() ?? []) as $f): ?>
        <div class="alert alert-<?= e($f['type']) ?>"><?= e($f['message']) ?></div>
    <?php endforeach; ?>
    <?= $content ?? '' ?>
</main>
</body>
</html>
