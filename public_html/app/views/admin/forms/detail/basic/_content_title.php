<?php /** c-formBlock01 直下 .area.is-field の内側: 基本設定ブロック — タイトル（name=title） */ ?>
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('タイトル') . ' <span data-module="m-required01" data-type="required">必須</span>', 'title'); ?>
        </div>
        <div class="area is-field">
            <?php admin_m_input01_open(); ?>
            <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('title', [
                'type' => 'text',
                'id' => 'title',
                'name' => 'title',
                'value' => (string) $form['title'],
                'required' => true,
            ])); ?>
            <?php admin_m_input01_close(); ?>
        </div>
        <?php admin_m_input_set01_close(); ?>
