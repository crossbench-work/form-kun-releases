<?php /** c-formBlock01 直下 .area.is-field の内側: 基本設定ブロック — スラッグ（name=slug） */ ?>
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('スラッグ（URL用）') . ' <span data-module="m-required01" data-type="required">必須</span>', 'slug'); ?>
        </div>
        <div class="area is-field">
            <?php admin_m_input01_open(); ?>
            <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('slug', [
                'type' => 'text',
                'id' => 'slug',
                'name' => 'slug',
                'value' => (string) $form['slug'],
                'required' => true,
            ])); ?>
            <?php admin_m_input01_close(); ?>
        </div>
        <div class="area is-help">
            <p class="text is-help"><a data-target="p-formEdit01__slugLink" href="<?= e(url('/f/' . $slugPreviewValue)) ?>"
                    target="_blank" rel="noopener"><code><?= e(url('/f/')) ?></code><strong
                        data-target="p-formEdit01__slugPreview"><?= e($slugPreviewValue) ?></strong></a></p>
        </div>
        <?php admin_m_input_set01_close(); ?>
