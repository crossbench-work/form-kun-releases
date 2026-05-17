<?php
/** @var string $namePrefix */
/** @var array<int, array<string, mixed>> $blocks */
/** @var string $positionMode */
?>
<div class="block is-field">
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('説明文')); ?>
    </div>
    <div class="area is-field">
        <div data-module="c-descriptionEditor01" data-description-editor data-name-prefix="<?= e($namePrefix) ?>"
            data-position-mode="<?= e($positionMode) ?>">
            <div class="wrapper">
                <div class="block is-contents">
                    <div class="c-formEditor c-formEditorList01" data-module="c-formEditorList01">
                        <div class="wrapper">
                            <div class="block is-contents" data-description-list>
                                <?php foreach ($blocks as $i => $block): ?>
                                    <?php
                                    $blockId = (string) ($block['id'] ?? ($namePrefix . '-' . $i));
                                    $labelValue = trim((string) ($block['label'] ?? ''));
                                    $labelInputId = $blockId . '-label';
                                    $positionInputId = $blockId . '-position';
                                    $htmlInputId = $blockId . '-html';
                                    $positionValue = (int) ($block['position'] ?? 0);
                                    $positionLabel = $positionValue === 0 ? '先頭' : '末尾';
                                    ?>
                                    <?php admin_c_form_editor_item01_open([
                                        'data-description-block' => true,
                                        'data-index' => (string) $i,
                                    ], true, empty($block['visible'])); ?>
                                    <input type="hidden" name="<?= e($namePrefix) ?>[<?= $i ?>][id]"
                                        value="<?= e($blockId) ?>" data-name="id">
                                    <div class="wrapper">
                                        <div class="block is-header">
                                            <?php admin_c_form_editor_item_header01_open(); ?>
                                            <div class="area is-move">
                                                <div class="box is-handle ui-sortable-handle" title="ドラッグして並べ替え">
                                                    <?php admin_m_drag_handle01('c-descriptionEditor__dragHandle'); ?>
                                                </div>
                                            </div>
                                            <div class="area is-summary">
                                                <div class="c-descriptionEditorSummary01">
                                                    <div class="wrapper">
                                                        <div class="block is-contents">
                                                            <p class="label"
                                                                data-target="c-descriptionEditor__summaryLabel"
                                                                <?= $labelValue === '' ? ' hidden' : '' ?>>
                                                                <?= $labelValue !== '' ? e($labelValue) : '' ?>
                                                            </p>
                                                            <p class="position"
                                                                data-target="c-descriptionEditor__summaryPosition">
                                                                <?= e($positionLabel) ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="area is-delete">
                                                <label class="c-descriptionEditorSummary01__visibility"
                                                    title="<?= !empty($block['visible']) ? '表示中' : '非表示' ?>">
                                                    <input type="checkbox"
                                                        name="<?= e($namePrefix) ?>[<?= $i ?>][visible]"
                                                        value="1" data-name="visible"
                                                        <?= !empty($block['visible']) ? 'checked' : '' ?>>
                                                    <span aria-hidden="true">
                                                        <svg width="16" height="16" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                                                            <circle cx="12" cy="12" r="3" />
                                                            <path class="is-hiddenLine" stroke-linecap="round"
                                                                stroke-linejoin="round" d="M4 20L20 4" />
                                                        </svg>
                                                    </span>
                                                </label>
                                                <?php admin_m_delete_btn01('fb-description-remove'); ?>
                                            </div>
                                            <?php admin_c_form_editor_item_header01_close(); ?>
                                        </div>
                                        <div class="block is-body">
                                            <?php admin_c_form_editor_item_body01_open(); ?>
                                            <div class="block is-contents">
                                                <div class="block is-field">
                                                    <?php admin_m_input_set01_open(); ?>
                                                    <div class="area is-title">
                                                        <?php admin_m_bold_title01(e('ラベル'), $labelInputId); ?>
                                                    </div>
                                                    <div class="area is-field">
                                                        <?php admin_m_input01_open(); ?>
                                                        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label($labelInputId, [
                                                            'type' => 'text',
                                                            'id' => $labelInputId,
                                                            'name' => $namePrefix . '[' . $i . '][label]',
                                                            'value' => (string) $labelValue,
                                                            'data-name' => 'label',
                                                            'data-target' => 'c-descriptionEditor__labelInput',
                                                        ])); ?>
                                                        <?php admin_m_input01_close(); ?>
                                                    </div>
                                                    <?php admin_m_input_set01_close(); ?>
                                                </div>
                                            </div>
                                            <div class="block is-field">
                                                <?php admin_m_input_set01_open(); ?>
                                                <div class="area is-title">
                                                    <?php admin_m_bold_title01(e('表示位置'), $positionInputId); ?>
                                                </div>
                                                <div class="area is-field">
                                                    <?php admin_m_input01_open(); ?>
                                                    <?php admin_m_input01_select_open(admin_m_input01_attrs_with_bound_label($positionInputId, [
                                                        'id' => $positionInputId,
                                                        'name' => $namePrefix . '[' . $i . '][position]',
                                                        'class' => 'fb-description-position',
                                                        'data-name' => 'position',
                                                        'data-value' => (string) $positionValue,
                                                    ])); ?>
                                                    <?php admin_m_input01_select_close(); ?>
                                                    <?php admin_m_input01_close(); ?>
                                                </div>
                                                <?php admin_m_input_set01_close(); ?>
                                            </div>
                                            <div class="block is-field">
                                        <?php admin_m_input_set01_open(); ?>
                                        <div class="area is-title">
                                            <?php admin_m_bold_title01(e('説明文'), $htmlInputId); ?>
                                        </div>
                                        <div class="area is-field">
                                            <div data-module="c-htmlInputEditor01" data-html-toggle-editor>
                                                <div class="wrapper">
                                                    <div class="block is-editor">
                                                        <?php admin_m_input01_open(); ?>
                                                        <?php admin_m_input01_textarea(admin_m_input01_attrs_with_bound_label($htmlInputId, [
                                                            'id' => $htmlInputId,
                                                            'name' => $namePrefix . '[' . $i . '][html]',
                                                            'rows' => 6,
                                                            'data-name' => 'html',
                                                            'data-target' => 'c-htmlInputEditor01__textarea',
                                                        ]), (string) ($block['html'] ?? '')); ?>
                                                        <?php admin_m_input01_close(); ?>
                                                    </div>
                                                    <div class="block is-toolbar">
                                                        <label class="label is-choice c-htmlInputEditor01__toggle">
                                                            <input type="checkbox"
                                                                name="<?= e($namePrefix) ?>[<?= $i ?>][is_html]" value="1"
                                                                data-name="is_html"
                                                                data-target="c-htmlInputEditor01__toggle"
                                                                <?= !empty($block['is_html']) ? 'checked' : '' ?>>
                                                            HTMLで入力する
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php admin_m_input_set01_close(); ?>
                                            </div>
                                            <?php admin_c_form_editor_item_body01_close(); ?>
                                        </div>
                                    </div>
                                    <?php admin_c_form_editor_item01_close(); ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block is-toolbar">
                    <div class="area is-actions">
                        <?php admin_m_btn01_open(); ?>
                        <?php admin_m_btn01_button('＋ 項目を追加', [
                            'data-target' => 'm-formBuilder01__add',
                            'class' => 'fb-description-add',
                        ]); ?>
                        <?php admin_m_btn01_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php admin_m_input_set01_close(); ?>
</div>
