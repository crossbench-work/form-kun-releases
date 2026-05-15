<?php
/**
 * 管理画面パネル c-formBlock01 のマークアップヘルパー。
 * スタイルは同ディレクトリの _c-formBlock01.scss（_admin-app.scss から @use）。
 */
if (!function_exists('admin_c_form_block01_open')) {
    /**
     * @param array<string, string>|null $contentsAttrs `.block.is-contents` に付与する追加属性（名・値とも e() で出力）
     */
    function admin_c_form_block01_open(?array $contentsAttrs = null): void
    {
        echo '<div data-module="c-formBlock01"><div class="wrapper"><div class="block is-contents"';
        if ($contentsAttrs !== null && $contentsAttrs !== []) {
            foreach ($contentsAttrs as $name => $value) {
                $name = (string) $name;
                if ($name === '' || !preg_match('/^[a-zA-Z_:][-a-zA-Z0-9_:.]*$/', $name)) {
                    continue;
                }
                echo ' ' . e($name) . '="' . e((string) $value) . '"';
            }
        }
        echo '>';
    }

    function admin_c_form_block01_close(): void
    {
        echo '</div></div></div>';
    }
}
