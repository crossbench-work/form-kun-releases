<?php
/**
 * フォーム編集画面のタブシェル c-formEditTabs01 のマークアップヘルパー。
 * スタイルは同ディレクトリの _c-formEditTabs01.scss（_admin-app.scss から @use）。
 */
if (!function_exists('admin_c_form_edit_tabs01_open')) {
    function admin_c_form_edit_tabs01_open(): void
    {
        $tabs = [
            ['panel' => 'basic', 'label' => '基本', 'selected' => true],
            ['panel' => 'fields', 'label' => '入力画面', 'selected' => false],
            ['panel' => 'confirm', 'label' => '確認画面', 'selected' => false],
            ['panel' => 'thanks', 'label' => '完了画面', 'selected' => false],
            ['panel' => 'mail', 'label' => '通知', 'selected' => false],
        ];
        ?>
        <div data-module="c-formEditTabs01">
            <div class="wrapper">
                <div class="block is-tabs" role="tablist" aria-label="フォーム設定のセクション">
                    <?php foreach ($tabs as $tab): ?>
                        <div data-module="m-formEditTab01" data-target="c-formEditTabs01__tab"
                            data-tab-panel="<?= e($tab['panel']) ?>" role="tab"
                            aria-selected="<?= !empty($tab['selected']) ? 'true' : 'false' ?>"
                            tabindex="<?= !empty($tab['selected']) ? '0' : '-1' ?>"><?= e($tab['label']) ?></div>
                    <?php endforeach; ?>
                </div>
                <div class="block is-contents">
        <?php
    }

    function admin_c_form_edit_tabs01_close(): void
    {
        echo '</div></div></div>';
    }
}
