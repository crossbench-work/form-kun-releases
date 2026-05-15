<!doctype html>
<html lang="ja">
<head>
<?php
$pageTitle = trim((string) ($title ?? 'フォーム'));
$resolvedMetaTitle = trim((string) ($metaTitle ?? ($form['meta_title'] ?? '')));
$resolvedMetaDescription = trim((string) ($metaDescription ?? ($form['meta_description'] ?? '')));
$documentTitle = $pageTitle !== '' ? $pageTitle : 'フォーム';
if ($resolvedMetaTitle !== '') {
    $documentTitle = ($pageTitle === '' || $pageTitle === $resolvedMetaTitle)
        ? $resolvedMetaTitle
        : $pageTitle . ' | ' . $resolvedMetaTitle;
}
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title><?= e($documentTitle) ?></title>
<?php if ($resolvedMetaDescription !== ''): ?>
<meta name="description" content="<?= e($resolvedMetaDescription) ?>">
<?php endif; ?>
<link rel="stylesheet" href="<?= e(base_path('/assets/css/public.css')) ?>">
</head>
<body>
<header class="l-siteHeader01">
    <div class="wrapper">
        <a class="link" href="<?= e(base_path('/')) ?>"><?= e(config('site.admin_name', 'フォーム')) ?></a>
    </div>
</header>

<main class="l-bodyMain01">
    <div class="wrapper">
<?php foreach ((flash() ?? []) as $f): ?>
    <div class="m-alert01 is-<?= e($f['type']) ?>"><?= e($f['message']) ?></div>
<?php endforeach; ?>

<?= $content ?? '' ?>
    </div>
</main>

<footer class="l-siteFooter01">
    <div class="wrapper">
        <small>&copy; <?= date('Y') ?> <?= e(config('site.admin_name', '')) ?></small>
    </div>
</footer>
<script>
window.FORMKUN_BASE = <?= json_encode(base_path(''), JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_HEX_APOS) ?>;
</script>
<script src="<?= e(base_path('/assets/js/public-form.js')) ?>" defer></script>
</body>
</html>
