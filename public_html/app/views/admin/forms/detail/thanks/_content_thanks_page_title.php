<?php /** c-formBlock01 直下 .area.is-field の内側: 完了画面 — ページタイトル（name=thanks_page_title） */ ?>
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('タイトル'), 'thanks_page_title'); ?>
            <label class="label is-choice">
                <input type="checkbox" name="show_thanks_page_title" value="1" <?= !empty($form['show_thanks_page_title']) ? 'checked' : '' ?>>
                表示する
            </label>
        </div>
        <div class="area is-field">
            <?php admin_m_input01_open(); ?>
            <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('thanks_page_title', [
                'type' => 'text',
                'id' => 'thanks_page_title',
                'name' => 'thanks_page_title',
                'value' => (string) ($form['thanks_page_title'] ?? ''),
            ])); ?>
            <?php admin_m_input01_close(); ?>
        </div>
        <?php admin_m_input_set01_close(); ?>
