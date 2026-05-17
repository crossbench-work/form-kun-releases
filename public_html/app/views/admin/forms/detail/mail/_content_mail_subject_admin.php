<?php /** c-formBlock01 直下 .area.is-field の内側: 管理者通知 — 件名（name=mail_subject_admin） */ ?>
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('件名'), 'mail_subject_admin'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('mail_subject_admin', [
            'type' => 'text',
            'id' => 'mail_subject_admin',
            'name' => 'mail_subject_admin',
            'value' => (string) $form['mail_subject_admin'],
            'placeholder' => '[{{__form_title__}}] 新規送信',
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
