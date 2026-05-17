<?php /** c-formBlock01 直下 .area.is-field の内側: 入力画面 — ページタイトル（name=input_page_title） */ ?>
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('タイトル'), 'input_page_title'); ?>
            <label class="label is-choice">
                <input type="checkbox" name="show_input_page_title" value="1" <?= !empty($form['show_input_page_title']) ? 'checked' : '' ?>>
                表示する
            </label>
        </div>
        <div class="area is-field">
            <?php admin_m_input01_open(); ?>
            <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('input_page_title', [
                'type' => 'text',
                'id' => 'input_page_title',
                'name' => 'input_page_title',
                'value' => (string) ($form['input_page_title'] ?? ''),
            ])); ?>
            <?php admin_m_input01_close(); ?>
        </div>
        <?php admin_m_input_set01_close(); ?>
