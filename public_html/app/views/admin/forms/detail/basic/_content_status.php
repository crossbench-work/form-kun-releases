<?php /** c-formBlock01 直下 .area.is-field の内側: 基本設定ブロック — 公開状態（name=status） */ ?>
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('公開状態'), 'status'); ?>
        </div>
        <div class="area is-field">
            <?php admin_m_input01_open(); ?>
            <?php admin_m_input01_select_open(admin_m_input01_attrs_with_bound_label('status', [
                'id' => 'status',
                'name' => 'status',
            ])); ?>
            <?php admin_m_input01_option('draft', '下書き', $form['status'] === 'draft'); ?>
            <?php admin_m_input01_option('active', '公開中', $form['status'] === 'active'); ?>
            <?php admin_m_input01_option('archived', '停止', $form['status'] === 'archived'); ?>
            <?php admin_m_input01_select_close(); ?>
            <?php admin_m_input01_close(); ?>
        </div>
        <?php admin_m_input_set01_close(); ?>
