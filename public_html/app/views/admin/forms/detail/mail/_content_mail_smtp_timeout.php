<?php /** c-formBlock01 直下 .area.is-field の内側: 通知設定 — タイムアウト（name=mail_smtp_timeout） */ ?>
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('タイムアウト（秒）'), 'mail_smtp_timeout'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('mail_smtp_timeout', [
            'type' => 'number',
            'id' => 'mail_smtp_timeout',
            'name' => 'mail_smtp_timeout',
            'value' => (string) ($form['mail_smtp_timeout'] ?? '10'),
            'min' => 1,
            'max' => 300,
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
