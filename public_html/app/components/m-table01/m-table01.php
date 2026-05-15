<?php
/**
 * 管理画面テーブル m-table01 のマークアップヘルパー。
 * スタイルは同ディレクトリの _m-table01.scss（_admin-app.scss から @use）。
 */
if (!function_exists('admin_m_table01_open')) {
    /**
     * @param string|null $extraClass 例: is-kv（スペース区切りで複数可）
     */
    function admin_m_table01_open(?string $extraClass = null): void
    {
        echo '<table data-module="m-table01"';
        $c = trim((string) $extraClass);
        if ($c !== '') {
            echo ' class="' . e($c) . '"';
        }
        echo '>';
    }

    function admin_m_table01_close(): void
    {
        echo '</table>';
    }

    /**
     * @param array<int, array{label: string, class?: string}> $columns th 見出し（任意で class、例 num）
     */
    function admin_m_table01_thead(array $columns): void
    {
        echo '<thead><tr>';
        foreach ($columns as $col) {
            $label = (string) ($col['label'] ?? '');
            $class = isset($col['class']) ? trim((string) $col['class']) : '';
            echo '<th';
            if ($class !== '') {
                echo ' class="' . e($class) . '"';
            }
            echo '>' . e($label) . '</th>';
        }
        echo '</tr></thead>';
    }
}
