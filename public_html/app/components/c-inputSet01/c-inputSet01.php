<?php
/**
 * 管理画面入力セット c-inputSet01 のマークアップヘルパー。
 * スタイルは同ディレクトリの _c-inputSet01.scss（_admin-app.scss から @use）。
 */
if (!function_exists('admin_c_input_set01_open')) {
    function admin_c_input_set01_open(): void
    {
        echo '<div data-module="c-inputSet01"><div class="wrapper"><div class="block is-contents">';
    }

    function admin_c_input_set01_close(): void
    {
        echo '</div></div></div>';
    }
}
