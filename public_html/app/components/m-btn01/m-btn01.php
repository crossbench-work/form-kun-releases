<?php
/**
 * 管理画面ボタンラッパー m-btn01 のマークアップヘルパー。
 * スタイルは同ディレクトリの _m-btn01.scss（_admin-app.scss から @use）。
 */
if (!function_exists('admin_m_btn01_open')) {
    /**
     * @param string|null $dataPosition 例: center のとき `data-position="center"` を付与
     */
    function admin_m_btn01_open(?string $dataPosition = null): void
    {
        echo '<div data-module="m-btn01"';
        if ($dataPosition === 'center') {
            echo ' data-position="center"';
        }
        echo '>';
    }

    function admin_m_btn01_close(): void
    {
        echo '</div>';
    }
}
