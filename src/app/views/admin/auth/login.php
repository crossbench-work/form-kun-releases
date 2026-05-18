<h2 class="title">ログイン</h2>

<form method="post" action="<?= e(base_path('/admin/login')) ?>" class="m-authForm01">
    <?= \Core\Csrf::field() ?>
    <div class="box is-field">
        <?php admin_m_bold_title01(e('ユーザー名'), 'username'); ?>
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('username', [
            'type' => 'text',
            'id' => 'username',
            'name' => 'username',
            'autocomplete' => 'username',
            'required' => true,
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <div class="box is-field">
        <?php admin_m_bold_title01(e('パスワード'), 'password'); ?>
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('password', [
            'type' => 'password',
            'id' => 'password',
            'name' => 'password',
            'autocomplete' => 'current-password',
            'required' => true,
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <div class="area is-actions">
        <button type="submit" class="btn is-primary">ログイン</button>
    </div>
    <p class="text is-muted" style="margin-top:16px;text-align:center;">
        <a href="<?= e(base_path('/admin/forgot-password')) ?>">パスワードをお忘れの場合</a>
    </p>
</form>
