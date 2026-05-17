<?php
/** フォーム編集:「完了画面」タブパネル
 * _content_*.php … c-formBlock01 直下 .area.is-field の内側。説明文は thanks_blocks エディタを本ファイルに残す。
 */
?>
<div data-target="c-formEditTabs01__contents" data-tab-panel="thanks" class="area is-contents" role="tabpanel"
    aria-label="完了画面" hidden>
    <?php admin_c_form_block01_open(); ?>
        <div class="area is-title">
            <?php admin_m_large_title01('表示設定'); ?>
            <?php admin_m_btn01_open(); ?>
            <button type="submit" class="btn" formaction="<?= e(base_path('/admin/forms/preview-thanks')) ?>"
                formmethod="post" formtarget="_blank" formnovalidate>プレビュー</button>
            <?php admin_m_btn01_close(); ?>
        </div>
        <div class="area is-field">
            <?php require __DIR__ . '/_content_thanks_page_title.php'; ?>
        </div>
        <?php admin_c_description_editor01('thanks_blocks', $thanksBlocks, 'simple'); ?>
    <?php admin_c_form_block01_close(); ?>

    <?php admin_c_form_block01_open(); ?>
        <div class="area is-title">
            <?php admin_m_large_title01('ボタン設定'); ?>
        </div>
        <div class="area is-field">
            <?php require __DIR__ . '/_content_thanks_buttons.php'; ?>
        </div>
    <?php admin_c_form_block01_close(); ?>
</div>
