<?php /** フォーム編集:「入力画面」タブパネル */ ?>
        <div data-target="c-formEditTabs01__contents" data-tab-panel="fields" class="area is-contents" role="tabpanel" aria-label="入力画面" hidden>
<?php admin_c_form_block01_open(); ?>
            <div class="block is-title">
                <?php admin_m_large_title01('表示設定'); ?>
                <?php admin_m_btn01_open(); ?>
                    <button
                        type="submit"
                        class="btn"
                        formaction="<?= e(base_path('/admin/forms/preview-input')) ?>"
                        formmethod="post"
                        formtarget="_blank"
                        formnovalidate
                    >プレビュー</button>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('タイトル'), 'input_page_title'); ?>
                                <label class="label is-choice">
                                    <input type="checkbox" name="show_input_page_title" value="1" <?= !empty($form['show_input_page_title']) ? 'checked' : '' ?>>
                                    表示する
                                </label>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <input type="text" id="input_page_title" name="input_page_title" value="<?= e($form['input_page_title'] ?? '') ?>"<?= admin_m_bold_title01_aria_labelledby_attr('input_page_title') ?>>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $renderDescriptionEditor('description_blocks', $descriptionBlocks, 'input'); ?>
                        </div>
                    </div>
                </div>
<?php admin_c_form_block01_open(['data-module' => 'm-formBuilder01']); ?>
            <div class="block is-title">
                <?php admin_m_large_title01('項目設定'); ?>
            </div>
            <div class="block is-field">
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
                </div>
            </div>
            </div>
                        </div>
                    </div>
                </div>
<?php admin_c_form_block01_open(); ?>
            <div class="block is-title">
                <?php admin_m_large_title01('ボタン設定'); ?>
            </div>
            <div class="block is-field">
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('ボタンテキスト'), 'confirm_button_text'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <input type="text" id="confirm_button_text" name="confirm_button_text" value="<?= e($form['confirm_button_text'] ?? '入力内容を確認する') ?>"<?= admin_m_bold_title01_aria_labelledby_attr('confirm_button_text') ?>>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                        </div>
                    </div>
                </div>
        </div>
