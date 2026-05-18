<?php
/**
 * 管理画面のコンパクト削除ボタン m-deleteBtn01 のマークアップヘルパー。
 * スタイルは同ディレクトリの _m-deleteBtn01.scss（_admin-app.scss から @use）。
 */
if (!function_exists('admin_m_delete_btn01')) {
    /**
     * ゴミ箱アイコン付きの削除ボタンを出力する。
     *
     * @param string $dataTarget `data-target`（JS が参照するフック）
     * @param string|null $ariaLabel 省略時は「削除」
     * @param string|null $title 省略時は「削除」
     */
    function admin_m_delete_btn01(string $dataTarget, ?string $ariaLabel = null, ?string $title = null): void
    {
        $aria = $ariaLabel ?? '削除';
        $ttl = $title ?? '削除';
        echo '<button type="button" data-module="m-deleteBtn01" data-target="' . e($dataTarget) . '" aria-label="' . e($aria) . '" title="' . e($ttl) . '">';
        echo '<svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/>';
        echo '<path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2"/>';
        echo '<path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6"/>';
        echo '<path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/>';
        echo '</svg>';
        echo '</button>';
    }
}
