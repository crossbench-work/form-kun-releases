<?php
/** フォーム編集:「基本」タブパネル
 * _content_*.php … c-formBlock01 直下 .area.is-field の内側（ラッパーは本ファイル）。ファイル名は主に name 属性に対応。
 */
?>
<div data-target="c-formEditTabs01__contents" data-tab-panel="basic" class="area is-contents" role="tabpanel"
    aria-label="基本">
    <?php admin_c_form_block01_open(); ?>
    <div class="area is-title">
        <?php admin_m_large_title01('基本設定'); ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_title.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_slug.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_status.php'; ?>
    </div>
    <?php admin_c_form_block01_close(); ?>

    <?php admin_c_form_block01_open(); ?>
    <div class="area is-title">
        <?php admin_m_large_title01('共通設定'); ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_meta_html.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_custom_css.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_custom_js.php'; ?>
    </div>
    <?php admin_c_form_block01_close(); ?>
</div>
