<?php /** c-formBlock01 直下 .area.is-field の内側: 共通設定ブロック — カスタム CSS（name=custom_css） */ ?>
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('css'), 'custom_css'); ?>
        </div>
        <div class="area is-field">
            <?php admin_m_input01_open(); ?>
            <?php admin_m_input01_textarea(admin_m_input01_attrs_with_bound_label('custom_css', [
                'id' => 'custom_css',
                'name' => 'custom_css',
                'rows' => 8,
                'placeholder' => ".p-formShow01 {\n  background: #fff;\n}",
            ]), (string) ($form['custom_css'] ?? '')); ?>
            <?php admin_m_input01_close(); ?>
        </div>
        <div class="area is-help">
            <p class="text is-help">公開フォーム専用の CSS をそのまま入力できます。</p>
        </div>
        <?php admin_m_input_set01_close(); ?>
