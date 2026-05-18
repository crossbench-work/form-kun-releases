<?php
/**
 * フォーム／説明文エディタのカード本文枠（.c-formEditor__itemBody）のマークアップヘルパー。
 * スタイルは同ディレクトリの _c-formEditorItemBody01.scss（_admin-app.scss から @use）。
 * 内側の `.block.is-contents` 以降は呼び出し側。
 */
if (!function_exists('admin_c_form_editor_item_body01_open')) {
    function admin_c_form_editor_item_body01_open(): void
    {
        echo '<div class="c-formEditor__itemBody">';
        echo '<div class="wrapper">';
    }

    function admin_c_form_editor_item_body01_close(): void
    {
        echo '</div></div>';
    }
}
