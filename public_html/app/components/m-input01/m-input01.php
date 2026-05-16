<?php
/**
 * 管理画面の入力ラッパー m-input01 のマークアップヘルパー。
 * スタイルは m-inputSet01 の _m-inputSet01.scss（[data-module="m-input01"] 含む）を参照。
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
