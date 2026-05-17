<?php /** c-formBlock01 直下 .area.is-field の内側: 自動返信 — 送信先フィールドキー（name=reply_from_field_key） */ ?>
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('送信先のフィールドキー（メール型項目）'), 'reply_from_field_key'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('reply_from_field_key', [
            'type' => 'text',
            'id' => 'reply_from_field_key',
            'name' => 'reply_from_field_key',
            'value' => (string) $form['reply_from_field_key'],
            'placeholder' => 'メールアドレス項目のフィールドキー（UUID または任意のキー）',
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
