<?php
/**
 * 説明文エディタ c-descriptionEditor01 のマークアップヘルパー。
 * スタイルは `_c-descriptionEditor01.scss`（_admin-app.scss から @use）。
 */
if (!function_exists('admin_c_description_editor01')) {
    function admin_c_description_editor01(string $namePrefix, array $blocks, string $positionMode): void
    {
        require __DIR__ . '/c-descriptionEditor01_markup.php';
    }
}
