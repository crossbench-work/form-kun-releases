<?php /** c-formBlock01 直下 .area.is-field の内側: フォームタブ — 項目設定（m-formBuilder01 一覧） */ ?>
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
