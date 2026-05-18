<?php /** c-formBlock01 直下 .area.is-field の内側: 共通設定ブロック — head / meta HTML（name=meta_html） */ ?>
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('head'), 'meta_html'); ?>
        </div>
        <div class="area is-field">
            <?php admin_m_input01_open(); ?>
            <?php admin_m_input01_textarea(admin_m_input01_attrs_with_bound_label('meta_html', [
                'id' => 'meta_html',
                'name' => 'meta_html',
                'rows' => 8,
                'placeholder' => '<meta name="description" content="フォームの概要">',
            ]), (string) ($form['meta_html'] ?? '')); ?>
            <?php admin_m_input01_close(); ?>
        </div>
        <div class="area is-help">
            <p class="text is-help">`&lt;meta ...&gt;` など、head に出したいHTMLをそのまま入力できます。</p>
        </div>
        <?php admin_m_input_set01_close(); ?>
