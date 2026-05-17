<?php /** c-formBlock01 直下 .area.is-field の内側: 通知設定 — SMTPパスワード（name=mail_smtp_password） */ ?>
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('SMTPパスワード'), 'mail_smtp_password'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('mail_smtp_password', [
            'type' => 'password',
            'id' => 'mail_smtp_password',
            'name' => 'mail_smtp_password',
            'value' => '',
            'autocomplete' => 'new-password',
        ])); ?>
        <?php admin_m_input01_close(); ?>
        <p class="text is-help">
            空欄のまま保存すると現在の値を保持します。<?= !empty($form['mail_smtp_password_configured']) ? '現在は設定済みです。' : '現在は未設定です。' ?></p>
        <?php if (!empty($form['mail_smtp_password_configured'])): ?>
            <label class="label is-choice">
                <input type="checkbox" name="mail_smtp_clear_password" value="1"
                    <?= !empty($form['mail_smtp_clear_password']) ? 'checked' : '' ?>>
                保存済みのSMTPパスワードを削除する
            </label>
        <?php endif; ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
