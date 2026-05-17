<template data-target="m-formBuilder01__descriptionTemplate">
    <?php admin_c_form_editor_item01_open([
        'data-description-block' => true,
    ]); ?>
        <input type="hidden" data-name="id" value="">
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
                                            <p class="label" data-target="c-descriptionEditor__summaryLabel" hidden></p>
                                            <p class="position" data-target="c-descriptionEditor__summaryPosition">先頭
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="area is-delete">
                                <label class="c-descriptionEditorSummary01__visibility" title="表示中">
                                    <input type="checkbox" data-name="visible" value="1" checked>
                                    <span aria-hidden="true">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                                            <circle cx="12" cy="12" r="3" />
                                            <path class="is-hiddenLine" stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 20L20 4" />
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
                                    <?php admin_m_bold_title01(e('ラベル')); ?>
                                </div>
                                <div class="area is-field">
                                    <?php admin_m_input01_open(); ?>
                                    <?php admin_m_input01_input([
                                        'type' => 'text',
                                        'data-name' => 'label',
                                        'data-target' => 'c-descriptionEditor__labelInput',
                                    ]); ?>
                                    <?php admin_m_input01_close(); ?>
                                </div>
                            </div>
                        </div>
                <?php admin_c_form_editor_item_body01_close(); ?>
                <div class="block is-field">
                    <?php admin_m_input_set01_open(); ?>
                    <div class="area is-title">
                        <?php admin_m_bold_title01(e('表示位置')); ?>
                    </div>
                    <div class="area is-field">
                        <?php admin_m_input01_open(); ?>
                        <?php admin_m_input01_select_open([
                            'class' => 'fb-description-position',
                            'data-name' => 'position',
                            'data-value' => '0',
                            'aria-label' => '説明文の表示位置',
                        ]); ?>
                        <?php admin_m_input01_select_close(); ?>
                        <?php admin_m_input01_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php admin_c_form_editor_item01_close(); ?>
    <div class="block is-field">
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('説明文')); ?>
        </div>
        <div class="area is-field">
            <div data-module="c-htmlInputEditor01" data-html-toggle-editor>
                <div class="wrapper">
                    <div class="block is-editor">
                        <?php admin_m_input01_open(); ?>
                        <?php admin_m_input01_textarea([
                            'rows' => 6,
                            'data-name' => 'html',
                            'data-target' => 'c-htmlInputEditor01__textarea',
                        ], ''); ?>
                        <?php admin_m_input01_close(); ?>
                    </div>
                    <div class="block is-toolbar">
                        <label class="label is-choice c-htmlInputEditor01__toggle">
                            <input type="checkbox" data-name="is_html" data-target="c-htmlInputEditor01__toggle"
                                value="1">
                            HTMLで入力する
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
</template>
