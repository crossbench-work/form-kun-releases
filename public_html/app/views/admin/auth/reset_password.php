<h2 class="title">新しいパスワードを設定</h2>

<?php if (!empty($errors)): ?>
    <div class="m-alert01 is-danger">
        <ul><?php foreach ($errors as $msg): ?><li><?= e($msg) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= e(base_path('/admin/reset-password')) ?>" class="m-authForm01">
    <?= \Core\Csrf::field() ?>
    <input type="hidden" name="token" value="<?= e($token) ?>">
    <div class="box is-field">
        <?php admin_m_bold_title01(e('新しいパスワード'), 'password'); ?>
        <div data-module="m-input01">
            <input type="password" id="password" name="password" autocomplete="new-password" required minlength="8"<?= admin_m_bold_title01_aria_labelledby_attr('password') ?>>
        </div>
        <p class="text is-help">8文字以上</p>
    </div>
    <div class="box is-field">
        <?php admin_m_bold_title01(e('新しいパスワード（確認）'), 'password_confirm'); ?>
        <div data-module="m-input01">
            <input type="password" id="password_confirm" name="password_confirm" autocomplete="new-password" required<?= admin_m_bold_title01_aria_labelledby_attr('password_confirm') ?>>
        </div>
    </div>
    <div class="area is-actions">
        <button type="submit" class="btn is-primary">設定する</button>
    </div>
</form>
