<h2 class="title">初期セットアップ</h2>
<p class="text is-muted">最初の管理者アカウントを作成します。</p>

<?php if (!empty($errors)): ?>
    <div class="m-alert01 is-danger">
        <ul>
        <?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= e(base_path('/admin/setup')) ?>" class="m-authForm01">
    <?= \Core\Csrf::field() ?>
    <div class="box is-field">
        <label class="label" for="username">ユーザー名</label>
        <input type="text" id="username" name="username" value="<?= e($old['username'] ?? '') ?>" required>
        <p class="text is-help">半角英数字・記号(_.-)で 3〜64 文字</p>
    </div>
    <div class="box is-field">
        <label class="label" for="display_name">表示名（任意）</label>
        <input type="text" id="display_name" name="display_name" value="<?= e($old['display_name'] ?? '') ?>">
    </div>
    <div class="box is-field">
        <label class="label" for="email">メールアドレス（任意・推奨）</label>
        <input type="email" id="email" name="email" value="<?= e($old['email'] ?? '') ?>" autocomplete="email">
        <p class="text is-help">パスワードを忘れたときに再設定メールを送るために使用します。</p>
    </div>
    <div class="box is-field">
        <label class="label" for="password">パスワード</label>
        <input type="password" id="password" name="password" required>
        <p class="text is-help">8文字以上を推奨</p>
    </div>
    <div class="box is-field">
        <label class="label" for="password_confirm">パスワード（確認）</label>
        <input type="password" id="password_confirm" name="password_confirm" required>
    </div>
    <div class="area is-actions">
        <button type="submit" class="btn is-primary">管理者を作成する</button>
    </div>
</form>
