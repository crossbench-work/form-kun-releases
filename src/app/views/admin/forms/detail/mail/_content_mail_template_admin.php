<?php /** c-formBlock01 直下 .area.is-field の内側: 管理者通知 — 本文テンプレート（name=mail_template_admin） */ ?>
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('表示設定'), 'mail_template_admin'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_textarea(admin_m_input01_attrs_with_bound_label('mail_template_admin', [
            'id' => 'mail_template_admin',
            'name' => 'mail_template_admin',
            'rows' => 6,
        ]), (string) $form['mail_template_admin']); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
