<h1><?= $isNew ? 'ユーザー新規作成' : 'ユーザー編集' ?></h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul><?php foreach ($errors as $msg): ?><li><?= e($msg) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= e($isNew ? base_path('/admin/users') : base_path('/admin/users/' . (int)$user['id'])) ?>">
    <?= \Core\Csrf::field() ?>

    <section class="panel">
        <div class="field">
            <label>ユーザー名</label>
            <?php if ($isNew): ?>
                <input type="text" name="username" value="<?= e($user['username']) ?>" required>
                <p class="field-help">半角英数字・記号(_.-)で 3〜64 文字。後から変更できません。</p>
            <?php else: ?>
                <input type="text" value="<?= e($user['username']) ?>" disabled>
            <?php endif; ?>
        </div>

        <div class="field">
            <label>表示名</label>
            <input type="text" name="display_name" value="<?= e($user['display_name'] ?? '') ?>">
        </div>

        <div class="field">
            <label>メールアドレス（任意）</label>
            <input type="email" name="email" value="<?= e($user['email'] ?? '') ?>" autocomplete="email">
            <p class="field-help">「パスワードを忘れたとき」に再設定リンクを送るために使用します。</p>
        </div>

        <div class="field">
            <label>権限</label>
            <select name="role">
                <option value="editor" <?= ($user['role'] ?? '') === 'editor' ? 'selected' : '' ?>>editor（フォーム/送信履歴の編集）</option>
                <option value="admin"  <?= ($user['role'] ?? '') === 'admin'  ? 'selected' : '' ?>>admin（ユーザー管理含む全権限）</option>
            </select>
        </div>

        <div class="field">
            <label>パスワード <?php if ($isNew): ?><span class="required">必須</span><?php endif; ?></label>
            <input type="password" name="password" autocomplete="new-password" <?= $isNew ? 'required' : '' ?>>
            <?php if (!$isNew): ?>
                <p class="field-help">変更しない場合は空のままにしてください。</p>
            <?php endif; ?>
        </div>
        <div class="field">
            <label>パスワード（確認）</label>
            <input type="password" name="password_confirm" autocomplete="new-password">
        </div>
    </section>

    <div class="actions">
        <button type="submit" class="btn btn-primary"><?= $isNew ? '作成する' : '保存する' ?></button>
        <a class="btn" href="<?= e(base_path('/admin/users')) ?>">キャンセル</a>
    </div>
</form>
