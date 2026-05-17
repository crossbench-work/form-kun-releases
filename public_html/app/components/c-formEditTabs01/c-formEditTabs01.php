<?php
/**
 * フォーム編集のセクション切替タブ c-formEditTabs01 のマークアップヘルパー。
 * タブボタン（m-formEditTab01）もここでまとめて出力する。
 * `data-target` / `data-tab-panel` / `role` は admin.js 互換のため維持する。
 */
if (!function_exists('admin_c_form_edit_tabs01_open')) {
    /**
     * @param array<int, array{panel: string, label: string, selected?: bool}>|null $tabsSpec
     */
    function admin_c_form_edit_tabs01_open(?array $tabsSpec = null): void
    {
        if ($tabsSpec === null) {
            $tabsSpec = [
                ['panel' => 'basic', 'label' => '基本', 'selected' => true],
                ['panel' => 'fields', 'label' => '入力画面'],
                ['panel' => 'confirm', 'label' => '確認画面'],
                ['panel' => 'thanks', 'label' => '完了画面'],
                ['panel' => 'mail', 'label' => '通知'],
            ];
        }

        echo '<div data-module="c-formEditTabs01">';
        echo '<div class="wrapper">';
        echo '<div class="block is-tabs" role="tablist" aria-label="フォーム設定のセクション">';
        foreach ($tabsSpec as $tab) {
            $panel = (string) ($tab['panel'] ?? '');
            $label = (string) ($tab['label'] ?? '');
            $selected = !empty($tab['selected']);
            echo '<div data-module="m-formEditTab01" data-target="c-formEditTabs01__tab"';
            echo ' data-tab-panel="' . e($panel) . '" role="tab"';
            echo ' aria-selected="' . ($selected ? 'true' : 'false') . '"';
            echo ' tabindex="' . ($selected ? '0' : '-1') . '">';
            echo e($label);
            echo '</div>';
        }
        echo '</div>';
        echo '<div class="block is-contents">';
    }

    function admin_c_form_edit_tabs01_close(): void
    {
        echo '</div></div></div>';
    }
}
