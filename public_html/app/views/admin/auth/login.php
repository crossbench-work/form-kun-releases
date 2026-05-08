<h2>ログイン</h2>

<form method="post" action="<?= e(base_path('/admin/login')) ?>" class="auth-form">
    <?= \Core\Csrf::field() ?>
    <div class="field">
        <label for="username">ユーザー名</label>
        <input type="text" id="username" name="username" autocomplete="username" required>
    </div>
    <div class="field">
        <label for="password">パスワード</label>
        <input type="password" id="password" name="password" autocomplete="current-password" required>
    </div>
    <div class="actions">
        <button type="submit" class="btn btn-primary">ログイン</button>
    </div>
</form>
