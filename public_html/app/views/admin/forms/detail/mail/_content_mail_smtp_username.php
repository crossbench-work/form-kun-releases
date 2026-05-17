<?php /** c-formBlock01 直下 .area.is-field の内側: 通知設定 — SMTPユーザー名（name=mail_smtp_username） */ ?>
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('SMTPユーザー名'), 'mail_smtp_username'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('mail_smtp_username', [
            'type' => 'text',
            'id' => 'mail_smtp_username',
            'name' => 'mail_smtp_username',
            'value' => (string) ($form['mail_smtp_username'] ?? ''),
            'autocomplete' => 'off',
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
