<?php
/** フォーム編集:「確認画面」タブパネル
 * _content_*.php … c-formBlock01 直下 .area.is-field の内側。説明文は confirm_blocks エディタを本ファイルに残す。
 */
?>
<div data-target="c-formEditTabs01__contents" data-tab-panel="confirm" class="area is-contents" role="tabpanel"
    aria-label="確認画面" hidden>
    <?php admin_c_form_block01_open(); ?>
    <div class="area is-title">
        <?php admin_m_large_title01('表示設定'); ?>
        <?php admin_m_btn01_open(); ?>
        <button type="submit" class="btn" formaction="<?= e(base_path('/admin/forms/preview-confirm')) ?>"
            formmethod="post" formtarget="_blank" formnovalidate>プレビュー</button>
        <?php admin_m_btn01_close(); ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_confirm_page_title.php'; ?>
    </div>
    <?php admin_c_description_editor01('confirm_blocks', $confirmBlocks, 'simple'); ?>
    <?php admin_c_form_block01_close(); ?>

    <?php admin_c_form_block01_open(); ?>
    <div class="area is-title">
        <?php admin_m_large_title01('ボタン設定'); ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_confirm_buttons.php'; ?>
    </div>
    <?php admin_c_form_block01_close(); ?>
</div>
