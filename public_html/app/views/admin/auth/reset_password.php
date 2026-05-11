<h2>新しいパスワードを設定</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul><?php foreach ($errors as $msg): ?><li><?= e($msg) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= e(base_path('/admin/reset-password')) ?>" class="auth-form">
    <?= \Core\Csrf::field() ?>
    <input type="hidden" name="token" value="<?= e($token) ?>">
    <div class="field">
        <label for="password">新しいパスワード</label>
        <input type="password" id="password" name="password" autocomplete="new-password" required minlength="8">
        <p class="field-help">8文字以上</p>
    </div>
    <div class="field">
        <label for="password_confirm">新しいパスワード（確認）</label>
        <input type="password" id="password_confirm" name="password_confirm" autocomplete="new-password" required>
    </div>
    <div class="actions">
        <button type="submit" class="btn btn-primary">設定する</button>
    </div>
</form>
