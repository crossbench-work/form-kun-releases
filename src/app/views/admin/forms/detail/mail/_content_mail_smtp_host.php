<?php /** c-formBlock01 直下 .area.is-field の内側: 通知設定 — SMTPホスト（name=mail_smtp_host） */ ?>
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('SMTPホスト'), 'mail_smtp_host'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('mail_smtp_host', [
            'type' => 'text',
            'id' => 'mail_smtp_host',
            'name' => 'mail_smtp_host',
            'value' => (string) ($form['mail_smtp_host'] ?? ''),
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
