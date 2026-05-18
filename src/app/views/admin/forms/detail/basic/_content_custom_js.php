<?php /** c-formBlock01 直下 .area.is-field の内側: 共通設定ブロック — カスタム JS（name=custom_js） */ ?>
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('js'), 'custom_js'); ?>
        </div>
        <div class="area is-field">
            <?php admin_m_input01_open(); ?>
            <?php admin_m_input01_textarea(admin_m_input01_attrs_with_bound_label('custom_js', [
                'id' => 'custom_js',
                'name' => 'custom_js',
                'rows' => 8,
                'placeholder' => "document.addEventListener('DOMContentLoaded', function () {\n  console.log('form loaded');\n});",
            ]), (string) ($form['custom_js'] ?? '')); ?>
            <?php admin_m_input01_close(); ?>
        </div>
        <div class="area is-help">
            <p class="text is-help">公開フォーム専用の JavaScript をそのまま入力できます。</p>
        </div>
        <?php admin_m_input_set01_close(); ?>
