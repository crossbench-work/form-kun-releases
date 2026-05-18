<?php
/**
 * 説明文エディタ c-descriptionEditor01 のマークアップヘルパー。
 * 本体は `c-descriptionEditor01_editor.php`。
 */
if (!function_exists('admin_c_description_editor01')) {
    /**
     * @param string $namePrefix フォーム name の接頭辞（例: description_blocks）
     * @param array<int, array<string, mixed>> $blocks 説明文ブロック配列
     * @param string $positionMode data-position-mode 属性値（input | simple）
     */
    function admin_c_description_editor01(string $namePrefix, array $blocks, string $positionMode): void
    {
        require __DIR__ . '/c-descriptionEditor01_editor.php';
    }
}
