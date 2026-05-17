<?php
/**
 * 管理画面のアラート c-alert01 のマークアップヘルパー。
 * スタイルは同ディレクトリの _c-alert01.scss（_admin-app.scss から @use）。
 */
if (!function_exists('admin_c_alert01_open')) {
    function admin_c_alert01_open(): void
    {
        echo '<div data-module="c-alert01"><div class="wrapper"><div class="block is-contents">';
    }

    function admin_c_alert01_close(): void
    {
        echo '</div></div></div>';
    }
}
