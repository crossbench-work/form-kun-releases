<?php
/**
 * 管理画面大見出し m-largeTitle01 のマークアップヘルパー。
 * スタイルは同ディレクトリの _m-largeTitle01.scss（_admin-app.scss から @use）。
 */
if (!function_exists('admin_m_large_title01')) {
    /**
     * @param string $text 見出し文言（e() でエスケープ）
     * @param string $headingTag h1〜h6（既定 h2）
     */
    function admin_m_large_title01(string $text, string $headingTag = 'h2'): void
    {
        $tag = strtolower($headingTag);
        if (!preg_match('/^h[1-6]$/', $tag)) {
            $tag = 'h2';
        }
        echo '<' . $tag . ' data-module="m-largeTitle01">' . e($text) . '</' . $tag . '>';
    }
}
