<h2>パスワード再設定</h2>
<p class="muted">ログインで使用している<strong>ユーザー名</strong>または<strong>登録済みメールアドレス</strong>を入力してください。メールが登録されている場合のみ、再設定用のリンクを送信します。</p>

<form method="post" action="<?= e(base_path('/admin/forgot-password')) ?>" class="auth-form">
    <?= \Core\Csrf::field() ?>
    <div class="field">
        <label for="identifier">ユーザー名またはメールアドレス</label>
        <input type="text" id="identifier" name="identifier" autocomplete="username" required>
    </div>
    <div class="actions">
        <button type="submit" class="btn btn-primary">再設定メールを送る</button>
        <a class="btn" href="<?= e(base_path('/admin/login')) ?>">ログインへ戻る</a>
    </div>
</form>
