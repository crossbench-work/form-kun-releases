<h2 class="title">パスワード再設定</h2>
<p class="text is-muted">ログインで使用している<strong>ユーザー名</strong>または<strong>登録済みメールアドレス</strong>を入力してください。メールが登録されている場合のみ、再設定用のリンクを送信します。</p>

<form method="post" action="<?= e(base_path('/admin/forgot-password')) ?>" class="m-authForm01">
    <?= \Core\Csrf::field() ?>
    <div class="box is-field">
        <label class="label" for="identifier">ユーザー名またはメールアドレス</label>
        <input data-module="m-inout01" type="text" id="identifier" name="identifier" autocomplete="username" required>
    </div>
    <div class="area is-actions">
        <button type="submit" class="btn is-primary">再設定メールを送る</button>
        <a class="btn" href="<?= e(base_path('/admin/login')) ?>">ログインへ戻る</a>
    </div>
</form>
