<?php /** c-formBlock01 直下 .area.is-field の内側: 通知設定 — 送信方式（name=mail_transport） */ ?>
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('送信方式'), 'mail_transport'); ?>
        </div>
        <div class="area is-field">
            <?php admin_m_input01_open(); ?>
            <?php admin_m_input01_select_open(admin_m_input01_attrs_with_bound_label('mail_transport', [
                'id' => 'mail_transport',
                'name' => 'mail_transport',
            ])); ?>
            <?php admin_m_input01_option('mail', 'PHP mail() / mb_send_mail()', ($form['mail_transport'] ?? 'mail') === 'mail'); ?>
            <?php admin_m_input01_option('smtp', 'SMTP', ($form['mail_transport'] ?? '') === 'smtp'); ?>
            <?php admin_m_input01_select_close(); ?>
            <?php admin_m_input01_close(); ?>
        </div>
    <?php admin_m_input_set01_close(); ?>
