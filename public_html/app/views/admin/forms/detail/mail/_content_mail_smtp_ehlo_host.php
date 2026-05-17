<?php /** c-formBlock01 直下 .area.is-field の内側: 通知設定 — EHLOホスト（name=mail_smtp_ehlo_host） */ ?>
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('EHLO ホスト名'), 'mail_smtp_ehlo_host'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('mail_smtp_ehlo_host', [
            'type' => 'text',
            'id' => 'mail_smtp_ehlo_host',
            'name' => 'mail_smtp_ehlo_host',
            'value' => (string) ($form['mail_smtp_ehlo_host'] ?? ''),
            'placeholder' => 'localhost',
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
