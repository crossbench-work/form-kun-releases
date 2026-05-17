<?php /** c-formBlock01 直下 .area.is-field の内側: 管理者通知 — 送信先（name=admin_emails） */ ?>
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('送信先メールアドレス（カンマ区切りで複数可）'), 'admin_emails'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('admin_emails', [
            'type' => 'text',
            'id' => 'admin_emails',
            'name' => 'admin_emails',
            'value' => (string) $form['admin_emails'],
            'placeholder' => 'admin@example.com, sub@example.com',
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
