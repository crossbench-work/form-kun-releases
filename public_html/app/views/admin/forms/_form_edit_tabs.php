<?php
/**
 * フォーム編集画面: セクション切替タブ（基本 / 入力画面 / 確認画面 / 完了画面 / 通知）
 * c-formEditTabs01 の開始〜各タブパネルを包む .block.is-contents の直前まで。
 * 各 data-tab-panel の中身は edit.php に記述する。
 */
$formEditTabsSpec = [
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
                        <?php foreach ($formEditTabsSpec as $tab): ?>
                        <div data-module="m-formEditTab01" data-target="c-formEditTabs01__tab" data-tab-panel="<?= e($tab['panel']) ?>" role="tab" aria-selected="<?= !empty($tab['selected']) ? 'true' : 'false' ?>" tabindex="<?= !empty($tab['selected']) ? '0' : '-1' ?>"><?= e($tab['label']) ?></div>
                        <?php endforeach; ?>
                        <?php unset($formEditTabsSpec); ?>
                    </div>

                    <div class="block is-contents">
