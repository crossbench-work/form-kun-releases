<!doctype html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title><?= e($title ?? '管理画面') ?> | <?= e(config('site.admin_name')) ?></title>
<link rel="stylesheet" href="<?= e(base_path('/assets/css/admin.css')) ?>">
</head>
<body class="admin-minimal">
<main class="auth-container">
    <h1 class="auth-title"><?= e(config('site.admin_name')) ?></h1>

    <?php foreach ((flash() ?? []) as $f): ?>
        <div class="alert alert-<?= e($f['type']) ?>"><?= e($f['message']) ?></div>
    <?php endforeach; ?>

    <?= $content ?? '' ?>
</main>
</body>
</html>
