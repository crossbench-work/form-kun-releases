<h2 class="title">パスワード再設定</h2>
<p class="text is-muted">ログインで使用している<strong>ユーザー名</strong>または<strong>登録済みメールアドレス</strong>を入力してください。メールが登録されている場合のみ、再設定用のリンクを送信します。</p>

<form method="post" action="<?= e(base_path('/admin/forgot-password')) ?>" class="m-authForm01">
    <?= \Core\Csrf::field() ?>
    <div class="box is-field">
        <?php admin_m_bold_title01(e('ユーザー名またはメールアドレス'), 'identifier'); ?>
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('identifier', [
            'type' => 'text',
            'id' => 'identifier',
            'name' => 'identifier',
            'autocomplete' => 'username',
            'required' => true,
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <div class="area is-actions">
        <button type="submit" class="btn is-primary">再設定メールを送る</button>
        <a class="btn" href="<?= e(base_path('/admin/login')) ?>">ログインへ戻る</a>
    </div>
</form>
