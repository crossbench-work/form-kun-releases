<?php /** フォーム編集:「フォーム」タブパネル */ ?>
<div data-target="c-formEditTabs01__contents" data-tab-panel="form" class="area is-contents" role="tabpanel"
    aria-label="フォーム" hidden>
    <?php admin_c_form_block01_open(['data-module' => 'm-formBuilder01']); ?>
    <div class="area is-title">
        <?php admin_m_large_title01('項目設定'); ?>
    </div>
    <div class="area is-field">
        <div class="c-formEditor c-formEditorList01" data-module="c-formEditorList01">
            <div class="wrapper">
                <div class="block is-contents" data-target="m-formBuilder01__fields">
                    <?php foreach ($fields as $i => $f): ?>
                        <?php $idx = $i; ?>
                        <?php include __DIR__ . '/_field_editor.php'; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="area is-actions">
            <?php admin_m_btn01_open(); ?>
            <button type="button" data-target="m-formBuilder01__add" class="btn">＋ 項目を追加</button>
            <?php admin_m_btn01_close(); ?>
        </div>
    </div>
    <?php admin_c_form_block01_close(); ?>
</div>
