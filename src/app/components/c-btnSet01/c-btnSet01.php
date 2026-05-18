<?php
/**
 * 管理画面のボタンセット c-btnSet01 のマークアップヘルパー。
 * スタイルは同ディレクトリの _c-btnSet01.scss（_admin-app.scss から @use）。
 */
if (!function_exists('admin_c_btn_set01_open')) {
    function admin_c_btn_set01_open(): void
    {
        echo '<div data-module="c-btnSet01"><div class="wrapper"><div class="block is-contents">';
    }

    function admin_c_btn_set01_close(): void
    {
        echo '</div></div></div>';
    }
}
