<?php /** c-formBlock01 直下 .area.is-field の内側: 自動返信 — 本文テンプレート（name=reply_template） */ ?>
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('表示設定'), 'reply_template'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_textarea(admin_m_input01_attrs_with_bound_label('reply_template', [
            'id' => 'reply_template',
            'name' => 'reply_template',
            'rows' => 6,
        ]), (string) $form['reply_template']); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
