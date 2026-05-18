<?php /** c-formBlock01 直下 .area.is-field の内側: 通知設定 — SMTPポート（name=mail_smtp_port） */ ?>
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('SMTPポート'), 'mail_smtp_port'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('mail_smtp_port', [
            'type' => 'number',
            'id' => 'mail_smtp_port',
            'name' => 'mail_smtp_port',
            'value' => (string) ($form['mail_smtp_port'] ?? '25'),
            'min' => 1,
            'max' => 65535,
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
