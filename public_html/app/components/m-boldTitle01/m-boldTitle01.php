<?php
/**
 * 管理画面の小見出し m-boldTitle01（p[data-module="m-boldTitle01"]）。
 * スタイルは同ディレクトリの _m-boldTitle01.scss（_admin-app.scss から @use）。
 */
if (!function_exists('admin_m_bold_title01')) {
    /**
     * @param string $innerHtml p 内の HTML（呼び出し側で e() 等を適用した断片を組み立てる）
     * @param string|null $boundInputId 直後の入力・選択の id と同一の文字列。p の id は `{boundInputId}-mBoldTitle01`
     */
    function admin_m_bold_title01(string $innerHtml, ?string $boundInputId = null): void
    {
        echo '<p data-module="m-boldTitle01"';
        if ($boundInputId !== null && $boundInputId !== '') {
            echo ' id="' . e($boundInputId) . '-mBoldTitle01"';
        }
        echo '>' . $innerHtml . '</p>';
    }

    /**
     * admin_m_bold_title01(..., $boundInputId) と対になる input / textarea / select 用（先頭スペース付き）。
     */
    function admin_m_bold_title01_aria_labelledby_attr(?string $boundInputId): string
    {
        if ($boundInputId === null || $boundInputId === '') {
            return '';
        }

        return ' aria-labelledby="' . e($boundInputId) . '-mBoldTitle01"';
    }
}
