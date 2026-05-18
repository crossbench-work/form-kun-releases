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
                                <?php admin_m_bold_title01(e('ユーザー名'), 'admin-user-username'); ?>
                                <?php if ($isNew): ?>
                                    <?php admin_m_input01_open(); ?>
                                        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('admin-user-username', [
                                            'type' => 'text',
                                            'id' => 'admin-user-username',
                                            'name' => 'username',
                                            'value' => (string) $user['username'],
                                            'required' => true,
                                        ])); ?>
                                    <?php admin_m_input01_close(); ?>
                                    <p class="text is-help">半角英数字・記号(_.-)で 3〜64 文字。後から変更できません。</p>
                                <?php else: ?>
                                    <?php admin_m_input01_open(); ?>
                                        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('admin-user-username', [
                                            'type' => 'text',
                                            'id' => 'admin-user-username',
                                            'value' => (string) $user['username'],
                                            'disabled' => true,
                                        ])); ?>
                                    <?php admin_m_input01_close(); ?>
                                <?php endif; ?>
                            </div>

                            <div class="area is-field">
                                <?php admin_m_bold_title01(e('表示名'), 'admin-user-display_name'); ?>
                                <?php admin_m_input01_open(); ?>
                                    <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('admin-user-display_name', [
                                        'type' => 'text',
                                        'id' => 'admin-user-display_name',
                                        'name' => 'display_name',
                                        'value' => (string) ($user['display_name'] ?? ''),
                                    ])); ?>
                                <?php admin_m_input01_close(); ?>
                            </div>

                            <div class="area is-field">
                                <?php admin_m_bold_title01(e('メールアドレス（任意）'), 'admin-user-email'); ?>
                                <?php admin_m_input01_open(); ?>
                                    <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('admin-user-email', [
                                        'type' => 'email',
                                        'id' => 'admin-user-email',
                                        'name' => 'email',
                                        'value' => (string) ($user['email'] ?? ''),
                                        'autocomplete' => 'email',
                                    ])); ?>
                                <?php admin_m_input01_close(); ?>
                                <p class="text is-help">「パスワードを忘れたとき」に再設定リンクを送るために使用します。</p>
                            </div>

                            <div class="area is-field">
                                <?php admin_m_bold_title01(e('権限'), 'admin-user-role'); ?>
                                <?php admin_m_input01_open(); ?>
                                    <?php admin_m_input01_select_open(admin_m_input01_attrs_with_bound_label('admin-user-role', [
                                        'id' => 'admin-user-role',
                                        'name' => 'role',
                                    ])); ?>
                                    <?php admin_m_input01_option('editor', 'editor（フォーム/送信履歴の編集）', ($user['role'] ?? '') === 'editor'); ?>
                                    <?php admin_m_input01_option('admin', 'admin（ユーザー管理含む全権限）', ($user['role'] ?? '') === 'admin'); ?>
                                    <?php admin_m_input01_select_close(); ?>
                                <?php admin_m_input01_close(); ?>
                            </div>

                            <div class="area is-field">
                                <?php admin_m_bold_title01(e('パスワード') . ($isNew ? ' <span data-module="m-required01" data-type="required">必須</span>' : ''), 'admin-user-password'); ?>
                                <?php
                                $adminUserPasswordAttrs = [
                                    'type' => 'password',
                                    'id' => 'admin-user-password',
                                    'name' => 'password',
                                    'autocomplete' => 'new-password',
                                ];
                                if ($isNew) {
                                    $adminUserPasswordAttrs['required'] = true;
                                }
                                ?>
                                <?php admin_m_input01_open(); ?>
                                    <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('admin-user-password', $adminUserPasswordAttrs)); ?>
                                <?php admin_m_input01_close(); ?>
                                <?php if (!$isNew): ?>
                                    <p class="text is-help">変更しない場合は空のままにしてください。</p>
                                <?php endif; ?>
                            </div>
                            <div class="area is-field">
                                <?php admin_m_bold_title01(e('パスワード（確認）'), 'admin-user-password_confirm'); ?>
                                <?php admin_m_input01_open(); ?>
                                    <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('admin-user-password_confirm', [
                                        'type' => 'password',
                                        'id' => 'admin-user-password_confirm',
                                        'name' => 'password_confirm',
                                        'autocomplete' => 'new-password',
                                    ])); ?>
                                <?php admin_m_input01_close(); ?>
                            </div>
<?php admin_c_form_block01_close(); ?>

    <div class="area is-actions">
        <button type="submit" class="btn is-primary"><?= $isNew ? '作成する' : '保存する' ?></button>
        <a class="btn" href="<?= e(base_path('/admin/users')) ?>">キャンセル</a>
    </div>
</form>
