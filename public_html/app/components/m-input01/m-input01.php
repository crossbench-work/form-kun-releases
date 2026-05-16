<?php
/**
 * 管理画面の入力ラッパー m-input01 のマークアップヘルパー。
 * スタイルは同ディレクトリの _m-input01.scss（_admin-app.scss から @use）。
 */
if (!function_exists('admin_m_input01_open')) {
    function admin_m_input01_open(): void
    {
        echo '<div data-module="m-input01">';
    }

    function admin_m_input01_close(): void
    {
        echo '</div>';
    }
}
