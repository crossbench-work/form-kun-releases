<?php
/**
 * 管理画面の入力ラッパー m-input01 のマークアップヘルパー。
 * スタイルは同ディレクトリの _m-input01.scss（_admin-app.scss から @use）。
 */
if (!function_exists('admin_m_input01_open')) {
    function admin_m_input01_open(): void
    {
        echo '<div data-module="m-input01">';
    }

    function admin_m_input01_close(): void
    {
        echo '</div>';
    }

    /**
     * HTML 属性の断片を組み立てる。値は e() でエスケープする（true は論理属性としてキーのみ出力）。
     *
     * @param array<string, string|int|float|bool|null> $attrs
     */
    function admin_m_input01_attrs(array $attrs): string
    {
        $parts = [];
        foreach ($attrs as $name => $value) {
            if ($value === null || $value === false) {
                continue;
            }
            $nameStr = (string) $name;
            if ($value === true) {
                $parts[] = e($nameStr);
                continue;
            }
            $parts[] = e($nameStr) . '="' . e((string) $value) . '"';
        }

        return implode(' ', $parts);
    }

    /**
     * admin_m_bold_title01(..., $boundInputId) と対になるコントロール用に aria-labelledby を付与した属性配列を返す。
     *
     * @param array<string, string|int|float|bool|null> $baseAttrs
     * @return array<string, string|int|float|bool|null>
     */
    function admin_m_input01_attrs_with_bound_label(?string $boundInputId, array $baseAttrs): array
    {
        $ref = admin_m_bold_title01_label_element_id($boundInputId);
        if ($ref !== null) {
            $baseAttrs['aria-labelledby'] = $ref;
        }

        return $baseAttrs;
    }

    /**
     * @param array<string, string|int|float|bool|null> $attrs
     */
    function admin_m_input01_input(array $attrs): void
    {
        $s = admin_m_input01_attrs($attrs);
        echo '<input' . ($s !== '' ? ' ' . $s : '') . '>';
    }

    /**
     * @param array<string, string|int|float|bool|null> $attrs
     * @param string $innerPlain 本文（未エスケープ。関数内で e() する）
     */
    function admin_m_input01_textarea(array $attrs, string $innerPlain = ''): void
    {
        $s = admin_m_input01_attrs($attrs);
        echo '<textarea' . ($s !== '' ? ' ' . $s : '') . '>' . e($innerPlain) . '</textarea>';
    }

    /**
     * @param array<string, string|int|float|bool|null> $attrs
     */
    function admin_m_input01_select_open(array $attrs): void
    {
        $s = admin_m_input01_attrs($attrs);
        echo '<select' . ($s !== '' ? ' ' . $s : '') . '>';
    }

    function admin_m_input01_select_close(): void
    {
        echo '</select>';
    }

    function admin_m_input01_option(string $value, string $label, bool $selected = false): void
    {
        echo '<option value="' . e($value) . '"' . ($selected ? ' selected' : '') . '>' . e($label) . '</option>';
    }
}
