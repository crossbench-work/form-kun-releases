<?php
/**
 * 管理画面のドラッグハンドル m-dragHandle01 のマークアップヘルパー。
 * スタイルは同ディレクトリの _m-dragHandle01.scss（_admin-app.scss から @use）。
 * 親の `.box.is-handle.ui-sortable-handle`（title 等）は呼び出し側で包む。
 */
if (!function_exists('admin_m_drag_handle01')) {
    /**
     * 6 ドットのドラッグ用ハンドル（`<p>` ＋ SVG）を出力する。
     *
     * @param string $dataTarget `data-target`（JS が参照するフック）
     */
    function admin_m_drag_handle01(string $dataTarget): void
    {
        echo '<p data-module="m-dragHandle01" data-target="' . e($dataTarget) . '">';
        echo '<svg width="14" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="currentColor">';
        echo '<circle cx="9" cy="6" r="2"/><circle cx="15" cy="6" r="2"/>';
        echo '<circle cx="9" cy="12" r="2"/><circle cx="15" cy="12" r="2"/>';
        echo '<circle cx="9" cy="18" r="2"/><circle cx="15" cy="18" r="2"/>';
        echo '</svg>';
        echo '</p>';
    }
}
