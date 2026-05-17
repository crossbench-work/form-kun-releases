<?php /** c-formBlock01 直下 .area.is-field の内側: 確認画面 — 送信・戻るボタンテキスト（submit/back） */ ?>
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('送信ボタンテキスト'), 'submit_button_text'); ?>
        </div>
        <div class="area is-field">
            <?php admin_m_input01_open(); ?>
            <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('submit_button_text', [
                'type' => 'text',
                'id' => 'submit_button_text',
                'name' => 'submit_button_text',
                'value' => (string) ($form['submit_button_text'] ?? '送信する'),
            ])); ?>
            <?php admin_m_input01_close(); ?>
        </div>
        <?php admin_m_input_set01_close(); ?>
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('戻るボタンテキスト'), 'back_button_text'); ?>
        </div>
        <div class="area is-field">
            <?php admin_m_input01_open(); ?>
            <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('back_button_text', [
                'type' => 'text',
                'id' => 'back_button_text',
                'name' => 'back_button_text',
                'value' => (string) ($form['back_button_text'] ?? '入力に戻る'),
            ])); ?>
            <?php admin_m_input01_close(); ?>
        </div>
        <?php admin_m_input_set01_close(); ?>
