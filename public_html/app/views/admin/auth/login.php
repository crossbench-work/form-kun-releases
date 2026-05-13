<h2 class="title">ログイン</h2>

<form method="post" action="<?= e(base_path('/admin/login')) ?>" class="m-authForm01">
    <?= \Core\Csrf::field() ?>
    <div class="box is-field">
        <label class="label" for="username">ユーザー名</label>
        <input data-module="m-inout01" type="text" id="username" name="username" autocomplete="username" required>
    </div>
    <div class="box is-field">
        <label class="label" for="password">パスワード</label>
        <input data-module="m-inout01" type="password" id="password" name="password" autocomplete="current-password" required>
    </div>
    <div class="area is-actions">
        <button type="submit" class="btn is-primary">ログイン</button>
    </div>
    <p class="text is-muted" style="margin-top:16px;text-align:center;">
        <a href="<?= e(base_path('/admin/forgot-password')) ?>">パスワードをお忘れの場合</a>
    </p>
</form>
