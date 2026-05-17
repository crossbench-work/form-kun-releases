<?php /** c-formBlock01 直下 .area.is-field の内側: 入力画面 — 確認ボタンテキスト（name=confirm_button_text） */ ?>
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('ボタンテキスト'), 'confirm_button_text'); ?>
        </div>
        <div class="area is-field">
            <?php admin_m_input01_open(); ?>
            <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('confirm_button_text', [
                'type' => 'text',
                'id' => 'confirm_button_text',
                'name' => 'confirm_button_text',
                'value' => (string) ($form['confirm_button_text'] ?? '入力内容を確認する'),
            ])); ?>
            <?php admin_m_input01_close(); ?>
        </div>
        <?php admin_m_input_set01_close(); ?>
