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
        <?php admin_m_bold_title01(e('ユーザー名'), 'username'); ?>
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('username', [
            'type' => 'text',
            'id' => 'username',
            'name' => 'username',
            'value' => (string) ($old['username'] ?? ''),
            'required' => true,
        ])); ?>
        <?php admin_m_input01_close(); ?>
        <p class="text is-help">半角英数字・記号(_.-)で 3〜64 文字</p>
    </div>
    <div class="box is-field">
        <?php admin_m_bold_title01(e('表示名（任意）'), 'display_name'); ?>
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('display_name', [
            'type' => 'text',
            'id' => 'display_name',
            'name' => 'display_name',
            'value' => (string) ($old['display_name'] ?? ''),
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <div class="box is-field">
        <?php admin_m_bold_title01(e('メールアドレス（任意・推奨）'), 'email'); ?>
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('email', [
            'type' => 'email',
            'id' => 'email',
            'name' => 'email',
            'value' => (string) ($old['email'] ?? ''),
            'autocomplete' => 'email',
        ])); ?>
        <?php admin_m_input01_close(); ?>
        <p class="text is-help">パスワードを忘れたときに再設定メールを送るために使用します。</p>
    </div>
    <div class="box is-field">
        <?php admin_m_bold_title01(e('パスワード'), 'password'); ?>
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('password', [
            'type' => 'password',
            'id' => 'password',
            'name' => 'password',
            'required' => true,
        ])); ?>
        <?php admin_m_input01_close(); ?>
        <p class="text is-help">8文字以上を推奨</p>
    </div>
    <div class="box is-field">
        <?php admin_m_bold_title01(e('パスワード（確認）'), 'password_confirm'); ?>
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('password_confirm', [
            'type' => 'password',
            'id' => 'password_confirm',
            'name' => 'password_confirm',
            'required' => true,
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <div class="area is-actions">
        <button type="submit" class="btn is-primary">管理者を作成する</button>
    </div>
</form>
