<?php /** c-formBlock01 直下 .area.is-field の内側: 通知設定 — 差出人名（name=mail_from_name） */ ?>
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('差出人名'), 'mail_from_name'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('mail_from_name', [
            'type' => 'text',
            'id' => 'mail_from_name',
            'name' => 'mail_from_name',
            'value' => (string) ($form['mail_from_name'] ?? ''),
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
