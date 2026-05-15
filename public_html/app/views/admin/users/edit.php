<h1 class="title"><?= $isNew ? 'ユーザー新規作成' : 'ユーザー編集' ?></h1>

<?php if (!empty($errors)): ?>
    <div class="m-alert01 is-danger">
        <ul><?php foreach ($errors as $msg): ?><li><?= e($msg) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= e($isNew ? base_path('/admin/users') : base_path('/admin/users/' . (int)$user['id'])) ?>">
    <?= \Core\Csrf::field() ?>

    <?php require_once APP_COMPONENTS_DIR . '/c-formBlock01/c-formBlock01.php'; ?>
    <?php admin_c_form_block01_open(); ?>
                            <div class="area is-field">
                                <label class="label" data-module="m-boldTitle01">ユーザー名</label>
                                <?php if ($isNew): ?>
                                    <div data-module="m-input01">
                                        <input type="text" name="username" value="<?= e($user['username']) ?>" required>
                                    </div>
                                    <p class="text is-help">半角英数字・記号(_.-)で 3〜64 文字。後から変更できません。</p>
                                <?php else: ?>
                                    <div data-module="m-input01">
                                        <input type="text" value="<?= e($user['username']) ?>" disabled>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="area is-field">
                                <label class="label" data-module="m-boldTitle01">表示名</label>
                                <div data-module="m-input01">
                                    <input type="text" name="display_name" value="<?= e($user['display_name'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="area is-field">
                                <label class="label" data-module="m-boldTitle01">メールアドレス（任意）</label>
                                <div data-module="m-input01">
                                    <input type="email" name="email" value="<?= e($user['email'] ?? '') ?>" autocomplete="email">
                                </div>
                                <p class="text is-help">「パスワードを忘れたとき」に再設定リンクを送るために使用します。</p>
                            </div>

                            <div class="area is-field">
                                <label class="label" data-module="m-boldTitle01">権限</label>
                                <div data-module="m-input01">
                                    <select name="role">
                                        <option value="editor" <?= ($user['role'] ?? '') === 'editor' ? 'selected' : '' ?>>editor（フォーム/送信履歴の編集）</option>
                                        <option value="admin"  <?= ($user['role'] ?? '') === 'admin'  ? 'selected' : '' ?>>admin（ユーザー管理含む全権限）</option>
                                    </select>
                                </div>
                            </div>

                            <div class="area is-field">
                                <label class="label" data-module="m-boldTitle01">パスワード <?php if ($isNew): ?><span data-module="m-required01" data-type="required">必須</span><?php endif; ?></label>
                                <div data-module="m-input01">
                                    <input type="password" name="password" autocomplete="new-password" <?= $isNew ? 'required' : '' ?>>
                                </div>
                                <?php if (!$isNew): ?>
                                    <p class="text is-help">変更しない場合は空のままにしてください。</p>
                                <?php endif; ?>
                            </div>
                            <div class="area is-field">
                                <label class="label" data-module="m-boldTitle01">パスワード（確認）</label>
                                <div data-module="m-input01">
                                    <input type="password" name="password_confirm" autocomplete="new-password">
                                </div>
                            </div>
<?php admin_c_form_block01_close(); ?>

    <div class="area is-actions">
        <button type="submit" class="btn is-primary"><?= $isNew ? '作成する' : '保存する' ?></button>
        <a class="btn" href="<?= e(base_path('/admin/users')) ?>">キャンセル</a>
    </div>
</form>
