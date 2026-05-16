<?php
/**
 * フォーム／説明文エディタのカード見出し枠（.c-formEditor__itemHeader）のマークアップヘルパー。
 * スタイルは同ディレクトリの _c-formEditorItemHeader01.scss（_admin-app.scss から @use）。
 * 中身の `area is-move` / `is-summary` / `is-delete` は呼び出し側。
 */
if (!function_exists('admin_c_form_editor_item_header01_open')) {
    function admin_c_form_editor_item_header01_open(): void
    {
        echo '<div class="c-formEditor__itemHeader">';
        echo '<div class="wrapper">';
        echo '<div class="block is-contents">';
    }

    function admin_c_form_editor_item_header01_close(): void
    {
        echo '</div></div></div>';
    }
}
