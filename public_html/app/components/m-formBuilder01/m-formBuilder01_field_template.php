<template data-target="m-formBuilder01__fieldTemplate">
    <div class="c-formEditor__item is-collapsed" data-field-item="1">
        <div class="wrapper">
            <div class="block is-header">
                <?php admin_c_form_editor_item_header01_open(); ?>
                            <div class="area is-move">
                                <div class="box is-handle ui-sortable-handle" title="ドラッグして並べ替え">
                                    <?php admin_m_drag_handle01('c-formEditor__dragHandle'); ?>
                                </div>
                            </div>
                            <div class="area is-summary">
                                <div class="c-formEditorSummary01">
                                    <div class="wrapper">
                                        <div class="block is-contents">
                                            <p class="index" data-target="c-formEditor__summaryIndex">1</p>
                                            <p class="label" data-target="c-formEditor__summaryLabel"></p>
                                            <p class="type" data-target="c-formEditor__summaryType">
                                                <?= e(\Models\FormField::typeLabel('text_multi')) ?></p>
                                            <p class="required" data-target="c-formEditor__summaryRequired" hidden>必須
                                            </p>
                                            <p class="regex" data-target="c-formEditor__summaryRegexWrap" hidden><svg
                                                    width="12" height="12" viewBox="0 0 24 24" aria-hidden="true"
                                                    focusable="false" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M20 6L9 17l-5-5" />
                                                </svg><span data-target="c-formEditor__summaryRegex">なし</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="area is-delete">
                                <label class="c-formEditorSummary01__visibility" title="表示中">
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
                                <?php admin_m_delete_btn01('c-formEditor__remove'); ?>
                            </div>
                <?php admin_c_form_editor_item_header01_close(); ?>
            </div>
            <div class="block is-body">
                <?php admin_c_form_editor_item_body01_open(); ?>
                        <div class="block is-contents">
                            <div class="block is-title">
                                <p data-module="m-borderTitle01">基本設定</p>
                            </div>
                            <div class="block is-field">
                                <div class="c-formEditor__field">
                                    <div class="wrapper">
                                        <div class="block is-contents">
                                            <div class="area is-title"><label class="label"
                                                    data-module="m-labelTitle01">ラベル</label></div>
                                            <div class="area is-field"><?php admin_m_input01_open(); ?><?php admin_m_input01_input([
                                                  'type' => 'text',
                                                  'data-name' => 'label',
                                                  'data-target' => 'c-formEditor__labelInput',
                                              ]); ?><?php admin_m_input01_close(); ?></div>
                                        </div>
                                        <div class="block is-contents">
                                            <div class="area is-title">
                                                <div class="c-formEditor__fieldKeyColumn"><label class="label"
                                                        data-module="m-labelTitle01">フィールドキー</label></div>
                                            </div>
                                            <div class="area is-field">
                                                <div class="c-formEditor__fieldKey"><?php admin_m_input01_open(); ?><?php admin_m_input01_input([
                                                      'type' => 'text',
                                                      'data-name' => 'field_key',
                                                      'data-target' => 'c-formEditor__fieldKeyInput',
                                                      'class' => 'is-locked',
                                                      'readonly' => true,
                                                      'spellcheck' => 'false',
                                                  ]); ?><?php admin_m_input01_close(); ?><button
                                                        type="button" class="btn is-icon"
                                                        data-target="c-formEditor__fieldKeyLock" aria-label="キーを編集"
                                                        title="編集する" aria-pressed="true"><svg
                                                            data-target="c-formEditor__fieldKeyLockedIcon" width="18"
                                                            height="18" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                        </svg><svg data-target="c-formEditor__fieldKeyOpenIcon"
                                                            width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" style="display:none"
                                                            aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                                        </svg></button></div>
                                            </div>
                                        </div>
                                        <input type="hidden" data-name="type" data-target="c-formEditor__type"
                                            value="text_multi"
                                            data-type-label="<?= e(\Models\FormField::typeLabel('text_multi')) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="block is-contents">
                            <div class="block is-title">
                                <p data-module="m-borderTitle01">入力設定</p>
                            </div>
                            <div class="block is-field">
                                <div class="c-formEditor__field">
                                    <div class="wrapper">
                                        <div class="block is-contents c-formEditor__validation">
                                            <div class="area is-title">
                                                <label class="label" data-module="m-labelTitle01">入力チェック</label>
                                                <div data-module="m-requiredCheck01">
                                                    <label class="label is-choice">
                                                        <input type="checkbox" data-name="required" value="1"
                                                            data-target="c-formEditor__required" aria-label="必須にする">
                                                        <span>必須にする</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="area is-field">
                                                <div class="c-formEditor__validationControls">
                                                    <div data-target="c-formEditor__regex">
                                                        <div class="c-formEditor__regexList">
                                                            <div class="c-formEditor__regexRow">
                                                                <?php admin_m_input01_open(); ?>
                                                                <?php admin_m_input01_select_open([
                                                                    'data-target' => 'c-formEditor__regexKind',
                                                                    'aria-label' => '入力チェックの種類',
                                                                ]);
                                                                foreach ($regexPresetChoices as $patVal => $patLabel) {
                                                                    admin_m_input01_option((string) $patVal, (string) $patLabel, false);
                                                                }
                                                                admin_m_input01_option((string) $FB_REGEX_CHECKBOX_LIMIT, '選択数を設定する', false);
                                                                admin_m_input01_select_close();
                                                                ?>
                                                                <?php admin_m_input01_close(); ?>
                                                                <div class="c-formEditor__regexCustom"
                                                                    style="display:none">
                                                                    <?php admin_m_input01_open(); ?>
                                                                    <?php admin_m_input01_input([
                                                                        'type' => 'text',
                                                                        'data-target' => 'c-formEditor__regexCustomInput',
                                                                        'placeholder' => '正規表現',
                                                                        'spellcheck' => 'false',
                                                                        'autocomplete' => 'off',
                                                                    ]); ?>
                                                                    <?php admin_m_input01_close(); ?>
                                                                </div>
                                                                <input type="hidden"
                                                                    data-target="c-formEditor__regexValue" value="">
                                                            </div>
                                                        </div>
                                                        <input type="hidden" data-name="checkbox_limit_enabled"
                                                            data-target="c-formEditor__checkboxLimitEnabled" value="0">
                                                        <div class="c-formEditor__checkboxLimit" style="display:none">
                                                            <div class="wrapper">
                                                                <div class="block is-contents">
                                                                    <div class="area is-title"><label class="label"
                                                                            data-module="m-labelTitle01">最小選択数</label>
                                                                    </div>
                                                                    <div class="area is-field">
                                                                        <?php admin_m_input01_open(); ?><?php admin_m_input01_input([
                                                                              'type' => 'number',
                                                                              'min' => 1,
                                                                              'data-name' => 'checkbox_min_selections',
                                                                          ]); ?><?php admin_m_input01_close(); ?>
                                                                    </div>
                                                                </div>
                                                                <div class="block is-contents">
                                                                    <div class="area is-title"><label class="label"
                                                                            data-module="m-labelTitle01">最大選択数</label>
                                                                    </div>
                                                                    <div class="area is-field">
                                                                        <?php admin_m_input01_open(); ?><?php admin_m_input01_input([
                                                                              'type' => 'number',
                                                                              'min' => 1,
                                                                              'data-name' => 'checkbox_max_selections',
                                                                          ]); ?><?php admin_m_input01_close(); ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="block is-contents" data-target="c-formEditor__minMaxLength">
                                            <div class="area is-title"><label class="label"
                                                    data-module="m-labelTitle01">文字数制限（任意）</label></div>
                                            <div class="area is-field">
                                                <div class="c-formEditor__child__field">
                                                    <div class="wrapper">
                                                        <div class="block is-contents">
                                                            <div class="area is-title"><label
                                                                    data-module="m-labelSmallTitle01">最小</label></div>
                                                            <div class="area is-field"><?php admin_m_input01_open(); ?><?php admin_m_input01_input([
                                                                  'type' => 'number',
                                                                  'min' => 1,
                                                                  'data-name' => 'min_length',
                                                                  'placeholder' => '最小',
                                                              ]); ?><?php admin_m_input01_close(); ?></div>
                                                        </div>
                                                        <div class="block is-contents">
                                                            <div class="area is-title"><label
                                                                    data-module="m-labelSmallTitle01">最大</label></div>
                                                            <div class="area is-field"><?php admin_m_input01_open(); ?><?php admin_m_input01_input([
                                                                  'type' => 'number',
                                                                  'min' => 1,
                                                                  'data-name' => 'max_length',
                                                                  'placeholder' => '最大',
                                                              ]); ?><?php admin_m_input01_close(); ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="block is-field" data-target="c-formEditor__options" style="display:none">
                                <div class="c-formEditor__field">
                                    <div class="wrapper">
                                        <div class="block is-contents">
                                            <div class="area is-title">
                                                <label class="label" data-module="m-labelTitle01">選択肢（1行に1つ）</label>
                                            </div>
                                            <div class="area is-field">
                                                <div class="c-formEditor__choiceEditor" data-choice-editor>
                                                    <div class="c-formEditor__choiceList">
                                                        <div class="wrapper">
                                                            <div class="block is-contents"
                                                                data-target="c-formEditor__choiceListContents">
                                                                <div class="area is-contents">
                                                                    <div class="c-formEditor__choiceItem"
                                                                        data-choice-item>
                                                                        <div class="wrapper">
                                                                            <div class="block is-contents">
                                                                                <div class="area is-move">
                                                                                    <div class="box is-handle ui-sortable-handle"
                                                                                        title="ドラッグして並べ替え">
                                                                                        <?php admin_m_drag_handle01('c-formEditor__choiceDragHandle'); ?>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="c-formEditor__child__field">
                                                                                    <div class="wrapper">
                                                                                        <div class="block is-contents">
                                                                                            <div class="area is-field">
                                                                                                <?php admin_m_input01_open(); ?><?php admin_m_input01_input([
                                                                                                      'type' => 'text',
                                                                                                      'data-target' => 'c-formEditor__choiceInput',
                                                                                                      'placeholder' => '選択肢',
                                                                                                  ]); ?><?php admin_m_input01_close(); ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <?php admin_m_delete_btn01('c-formEditor__choiceRemove'); ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="area is-contents"
                                                                    data-target="c-formEditor__choiceControls">
                                                                    <?php admin_m_btn01_open('center'); ?>
                                                                    <button type="button" class="btn is-add"
                                                                        data-target="c-formEditor__choiceAdd">＋
                                                                        選択肢を追加</button>
                                                                    <textarea data-name="options"
                                                                        data-target="c-formEditor__optionsSource"
                                                                        rows="4" hidden></textarea>
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
                    <div class="block is-contents">
                        <div class="block is-title">
                            <p data-module="m-borderTitle01">表示設定</p>
                        </div>
                        <div class="block is-field">
                            <div class="c-formEditor__field">
                                <div class="wrapper">
                                    <div class="block is-contents" data-target="c-formEditor__placeholder">
                                        <div class="area is-title"><label class="label"
                                                data-module="m-labelTitle01">プレースホルダ（任意）</label></div>
                                        <div class="area is-field"><?php admin_m_input01_open(); ?><?php admin_m_input01_input([
                                              'type' => 'text',
                                              'data-name' => 'placeholder',
                                          ]); ?><?php admin_m_input01_close(); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="block is-field">
                            <div class="c-formEditor__field" data-target="c-formEditor__textMultiSettings"
                                style="display:none">
                                <div class="c-formEditor__textMultiList" data-text-multi-editor>
                                    <div class="wrapper">
                                        <div class="block is-contents"
                                            data-target="c-formEditor__textMultiListContents">
                                            <div class="area is-contents">
                                                <div class="c-formEditor__textMultiItem" data-text-multi-item>
                                                    <div class="wrapper">
                                                        <div class="block is-contents">
                                                            <div class="c-formEditor__child__field">
                                                                <div class="wrapper">
                                                                    <div class="block is-contents">
                                                                        <div class="area is-title"><label
                                                                                data-module="m-labelSmallTitle01">表示</label>
                                                                        </div>
                                                                        <div class="area is-field">
                                                                            <?php admin_m_input01_open(); ?><?php admin_m_input01_input([
                                                                                  'type' => 'text',
                                                                                  'data-target' => 'c-formEditor__textMultiLabelInput',
                                                                              ]); ?><?php admin_m_input01_close(); ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="block is-contents">
                                                                        <div class="area is-title"><label
                                                                                data-module="m-labelSmallTitle01">入力例</label>
                                                                        </div>
                                                                        <div class="area is-field">
                                                                            <?php admin_m_input01_open(); ?><?php admin_m_input01_input([
                                                                                  'type' => 'text',
                                                                                  'data-target' => 'c-formEditor__textMultiPlaceholderInput',
                                                                              ]); ?><?php admin_m_input01_close(); ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php admin_m_delete_btn01('c-formEditor__textMultiRemove'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="area is-contents" data-target="c-formEditor__textMultiControls">
                                                <?php admin_m_btn01_open('center'); ?>
                                                <button type="button" data-target="c-formEditor__textMultiAdd"
                                                    class="btn is-add fb-description-add">＋ 入力エリアを追加</button>
                                                <textarea data-name="multi_text_items"
                                                    data-target="c-formEditor__textMultiSource"
                                                    hidden>[{"label":"","placeholder":""}]</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block is-field">
                        <div class="c-formEditor__field">
                            <div class="wrapper">
                                <div class="block is-contents">
                                    <div class="area is-title">
                                        <label class="label" data-module="m-labelTitle01">説明文</label>
                                    </div>
                                    <div class="area is-field">
                                        <div data-module="c-htmlInputEditor01" data-html-toggle-editor>
                                            <div class="wrapper">
                                                <div class="block is-editor">
                                                    <?php admin_m_input01_open(); ?><?php admin_m_input01_textarea([
                                                          'data-name' => 'help_text',
                                                          'rows' => 3,
                                                          'data-target' => 'c-htmlInputEditor01__textarea',
                                                      ], ''); ?><?php admin_m_input01_close(); ?>
                                                </div>
                                                <div class="block is-toolbar">
                                                    <label class="label is-choice c-htmlInputEditor01__toggle">
                                                        <input type="checkbox" data-name="help_text_is_html"
                                                            data-target="c-htmlInputEditor01__toggle" value="1">
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
                    <input type="hidden" data-name="_remove" value="0" data-target="c-formEditor__removeFlag">
                <?php admin_c_form_editor_item_body01_close(); ?>
            </div>
        </div>
    </div>
    </div>
    </div>
</template>
