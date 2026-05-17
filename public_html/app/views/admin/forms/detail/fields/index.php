<?php
/** フォーム編集:「入力画面」タブパネル
 * _content_*.php … c-formBlock01 直下 .area.is-field の内側。説明文エディタはコンポーネント呼び出しのまま本ファイルに残す。
 */
?>
<div data-target="c-formEditTabs01__contents" data-tab-panel="fields" class="area is-contents" role="tabpanel"
    aria-label="入力画面" hidden>
    <?php admin_c_form_block01_open(); ?>
        <div class="area is-title">
            <?php admin_m_large_title01('表示設定'); ?>
            <?php admin_m_btn01_preview_wrap(base_path('/admin/forms/preview-input')); ?>
        </div>
        <div class="area is-field">
            <?php require __DIR__ . '/_content_input_page_title.php'; ?>
        </div>
        <?php admin_c_description_editor01('description_blocks', $descriptionBlocks, 'input'); ?>
    <?php admin_c_form_block01_close(); ?>

    <?php admin_c_form_block01_open(); ?>
        <div class="area is-title">
            <?php admin_m_large_title01('ボタン設定'); ?>
        </div>
        <div class="area is-field">
            <?php require __DIR__ . '/_content_confirm_button_text.php'; ?>
        </div>
    <?php admin_c_form_block01_close(); ?>
</div>
