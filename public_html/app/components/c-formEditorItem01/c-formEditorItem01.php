<?php
/**
 * フォーム／説明文エディタのカード枠（.c-formEditor__item）のマークアップヘルパー。
 * スタイルは同ディレクトリの _c-formEditorItem01.scss（_admin-app.scss から @use）。
 */
if (!function_exists('admin_c_form_editor_item01_open')) {
    function admin_c_form_editor_item01_open(array $attrs = [], bool $collapsed = true, bool $hidden = false): void
    {
        $classes = ['c-formEditor__item'];
        if ($collapsed) {
            $classes[] = 'is-collapsed';
        }
        if ($hidden) {
            $classes[] = 'is-editor-item-hidden';
        }
        if (isset($attrs['class']) && trim((string) $attrs['class']) !== '') {
            $classes[] = trim((string) $attrs['class']);
        }
        $attrs['class'] = implode(' ', $classes);

        echo '<div';
        foreach ($attrs as $name => $value) {
            if ($value === null || $value === false) {
                continue;
            }
            if ($value === true) {
                echo ' ' . e((string) $name);
                continue;
            }
            echo ' ' . e((string) $name) . '="' . e((string) $value) . '"';
        }
        echo '>';
    }

    function admin_c_form_editor_item01_close(): void
    {
        echo '</div>';
    }
}
