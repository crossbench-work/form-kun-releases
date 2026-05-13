<h2 class="title">新しいパスワードを設定</h2>

<?php if (!empty($errors)): ?>
    <div class="m-alert01 is-danger">
        <ul><?php foreach ($errors as $msg): ?><li><?= e($msg) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= e(base_path('/admin/reset-password')) ?>" class="m-authForm01">
    <?= \Core\Csrf::field() ?>
    <input type="hidden" name="token" value="<?= e($token) ?>">
    <div class="box is-field">
        <label class="label" for="password">新しいパスワード</label>
        <input data-module="m-inout01" type="password" id="password" name="password" autocomplete="new-password" required minlength="8">
        <p class="text is-help">8文字以上</p>
    </div>
    <div class="box is-field">
        <label class="label" for="password_confirm">新しいパスワード（確認）</label>
        <input data-module="m-inout01" type="password" id="password_confirm" name="password_confirm" autocomplete="new-password" required>
    </div>
    <div class="area is-actions">
        <button type="submit" class="btn is-primary">設定する</button>
    </div>
</form>
