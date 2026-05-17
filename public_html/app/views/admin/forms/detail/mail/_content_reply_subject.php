<?php /** c-formBlock01 直下 .area.is-field の内側: 自動返信 — 件名（name=reply_subject） */ ?>
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('件名'), 'reply_subject'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('reply_subject', [
            'type' => 'text',
            'id' => 'reply_subject',
            'name' => 'reply_subject',
            'value' => (string) $form['reply_subject'],
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
