<?php
/**
 * 管理画面のラベル行＋入力セット m-inputSet01 のマークアップヘルパー。
 * スタイルは同ディレクトリの _m-inputSet01.scss（_admin-app.scss から @use）。
 */
if (!function_exists('admin_m_input_set01_open')) {
    function admin_m_input_set01_open(): void
    {
        echo '<div data-module="m-inputSet01"><div class="wrapper"><div class="block is-contents">';
    }

    function admin_m_input_set01_close(): void
    {
        echo '</div></div></div>';
    }
}
