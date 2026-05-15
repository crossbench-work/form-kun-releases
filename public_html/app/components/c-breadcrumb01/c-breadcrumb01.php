<?php
/**
 * 管理画面パンくず c-breadcrumb01。
 * ルートは data-target="c-breadcrumb01"（data-module ではなく target キーで統一）。
 * スタイルは同ディレクトリの _c-breadcrumb01.scss（_admin-app.scss から @use）。
 */
if (!function_exists('admin_c_breadcrumb01')) {
    /**
     * @param list<array{label: string, href?: string, current?: bool}> $crumbs
     *        リンク行は href を指定。現在行は current を true にし、href は付けない（編集画面のタイトル用に span[data-target=p-formEdit01__breadcrumbCurrent] を出す）。
     */
    function admin_c_breadcrumb01(array $crumbs): void
    {
        echo '<div data-target="c-breadcrumb01"><div class="wrapper"><div class="block is-contents"><nav aria-label="パンくず"><ol>';
        foreach ($crumbs as $c) {
            $label = (string) ($c['label'] ?? '');
            $isCurrent = !empty($c['current']);
            $href = isset($c['href']) ? trim((string) $c['href']) : '';
            echo '<li';
            if ($isCurrent) {
                echo ' aria-current="page"';
            }
            echo '>';
            if ($href !== '') {
                echo '<a href="' . e($href) . '">' . e($label) . '</a>';
            } else {
                echo '<span data-target="p-formEdit01__breadcrumbCurrent">' . e($label) . '</span>';
            }
            echo '</li>';
        }
        echo '</ol></nav></div></div></div>';
    }
}
