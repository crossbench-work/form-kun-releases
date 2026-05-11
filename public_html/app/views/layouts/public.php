<!doctype html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title><?= e($title ?? 'フォーム') ?></title>
<link rel="stylesheet" href="<?= e(base_path('/assets/css/public.css')) ?>">
</head>
<body>
<header class="site-header">
    <div class="container">
        <a class="brand" href="<?= e(base_path('/')) ?>"><?= e(config('site.admin_name', 'フォーム')) ?></a>
    </div>
</header>

<main class="container">
<?php foreach ((flash() ?? []) as $f): ?>
    <div class="alert alert-<?= e($f['type']) ?>"><?= e($f['message']) ?></div>
<?php endforeach; ?>

<?= $content ?? '' ?>
</main>

<footer class="site-footer">
    <div class="container">
        <small>&copy; <?= date('Y') ?> <?= e(config('site.admin_name', '')) ?></small>
    </div>
</footer>
<script>
window.FORMKUN_BASE = <?= json_encode(base_path(''), JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_HEX_APOS) ?>;
</script>
<script src="<?= e(base_path('/assets/js/public-form.js')) ?>" defer></script>
</body>
</html>
