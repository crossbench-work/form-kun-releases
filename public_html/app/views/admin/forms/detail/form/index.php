<?php
/** フォーム編集:「フォーム」タブパネル（項目設定）
 * _content_items.php … 項目一覧・追加ボタン（m-formBuilder01）。各項目の詳細は _field_editor.php。
 */
?>
<div data-target="c-formEditTabs01__contents" data-tab-panel="form" class="area is-contents" role="tabpanel"
    aria-label="フォーム" hidden>
    <?php admin_c_form_block01_open(['data-module' => 'm-formBuilder01']); ?>
        <div class="area is-title">
            <?php admin_m_large_title01('項目設定'); ?>
        </div>
        <div class="area is-field">
            <?php require __DIR__ . '/_content_items.php'; ?>
        </div>
    <?php admin_c_form_block01_close(); ?>
</div>
