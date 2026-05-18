<?php /** c-formBlock01 直下 .area.is-field の内側: 通知設定 — 差出人メール（name=mail_from_email） */ ?>
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('差出人メールアドレス'), 'mail_from_email'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('mail_from_email', [
            'type' => 'email',
            'id' => 'mail_from_email',
            'name' => 'mail_from_email',
            'value' => (string) ($form['mail_from_email'] ?? ''),
            'placeholder' => 'noreply@example.com',
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
