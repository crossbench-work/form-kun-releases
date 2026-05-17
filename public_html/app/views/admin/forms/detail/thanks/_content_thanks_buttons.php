<?php /** c-formBlock01 直下 .area.is-field の内側: 完了画面 — 完了ボタン文言・URL（thanks_button_*） */ ?>
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('ボタンテキスト'), 'thanks_button_text'); ?>
        </div>
        <div class="area is-field">
            <?php admin_m_input01_open(); ?>
            <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('thanks_button_text', [
                'type' => 'text',
                'id' => 'thanks_button_text',
                'name' => 'thanks_button_text',
                'value' => (string) ($form['thanks_button_text'] ?? 'トップへ戻る'),
            ])); ?>
            <?php admin_m_input01_close(); ?>
        </div>
        <?php admin_m_input_set01_close(); ?>
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('ボタンURL'), 'thanks_button_url'); ?>
        </div>
        <div class="area is-field">
            <?php admin_m_input01_open(); ?>
            <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('thanks_button_url', [
                'type' => 'text',
                'id' => 'thanks_button_url',
                'name' => 'thanks_button_url',
                'value' => (string) ($form['thanks_button_url'] ?? '/'),
                'placeholder' => '/ または https://example.com/',
            ])); ?>
            <?php admin_m_input01_close(); ?>
        </div>
        <?php admin_m_input_set01_close(); ?>
