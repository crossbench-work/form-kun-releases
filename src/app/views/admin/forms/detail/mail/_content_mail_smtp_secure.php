<?php /** c-formBlock01 直下 .area.is-field の内側: 通知設定 — 暗号化方式（name=mail_smtp_secure） */ ?>
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('暗号化方式'), 'mail_smtp_secure'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_select_open(admin_m_input01_attrs_with_bound_label('mail_smtp_secure', [
            'id' => 'mail_smtp_secure',
            'name' => 'mail_smtp_secure',
        ])); ?>
        <?php admin_m_input01_option('', 'なし', ($form['mail_smtp_secure'] ?? '') === ''); ?>
        <?php admin_m_input01_option('ssl', 'SSL', ($form['mail_smtp_secure'] ?? '') === 'ssl'); ?>
        <?php admin_m_input01_option('tls', 'STARTTLS', ($form['mail_smtp_secure'] ?? '') === 'tls'); ?>
        <?php admin_m_input01_select_close(); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
