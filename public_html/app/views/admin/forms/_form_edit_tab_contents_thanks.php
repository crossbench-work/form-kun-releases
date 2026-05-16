<?php /** フォーム編集:「完了画面」タブパネル */ ?>
        <div data-target="c-formEditTabs01__contents" data-tab-panel="thanks" class="area is-contents" role="tabpanel" aria-label="完了画面" hidden>
<?php admin_c_form_block01_open(); ?>
            <div class="block is-title">
                <?php admin_m_large_title01('表示設定'); ?>
                <?php admin_m_btn01_open(); ?>
                    <button
                        type="submit"
                        class="btn"
                        formaction="<?= e(base_path('/admin/forms/preview-thanks')) ?>"
                        formmethod="post"
                        formtarget="_blank"
                        formnovalidate
                    >プレビュー</button>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_m_input_set01_open(); ?>
                            <div class="area is-title">
                                <?php admin_m_bold_title01(e('タイトル'), 'thanks_page_title'); ?>
                                <label class="label is-choice">
                                    <input type="checkbox" name="show_thanks_page_title" value="1" <?= !empty($form['show_thanks_page_title']) ? 'checked' : '' ?>>
                                    表示する
                                </label>
                            </div>
                            <div class="area is-field">
                                <?php admin_m_input01_open(); ?>
                                    <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('thanks_page_title', [
                                        'type' => 'text',
                                        'id' => 'thanks_page_title',
                                        'name' => 'thanks_page_title',
                                        'value' => (string) ($form['thanks_page_title'] ?? ''),
                                    ])); ?>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $renderDescriptionEditor('thanks_blocks', $thanksBlocks, 'simple'); ?>
                        </div>
                    </div>
                </div>
<?php admin_c_form_block01_open(); ?>
            <div class="block is-title">
                <?php admin_m_large_title01('ボタン設定'); ?>
            </div>
            <div class="block is-field">
                <?php admin_m_input_set01_open(); ?>
                            <div class="area is-title">
                                <?php admin_m_bold_title01(e('ボタンテキスト'), 'thanks_button_text'); ?>
                            </div>
                            <div class="area is-field">
                                <?php admin_m_input01_open(); ?>
                                    <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('thanks_button_text', [
                                        'type' => 'text',
                                        'id' => 'thanks_button_text',
                                        'name' => 'thanks_button_text',
                                        'value' => (string) ($form['thanks_button_text'] ?? 'トップへ戻る'),
                                    ])); ?>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_m_input_set01_open(); ?>
                            <div class="area is-title">
                                <?php admin_m_bold_title01(e('ボタンURL'), 'thanks_button_url'); ?>
                            </div>
                            <div class="area is-field">
                                <?php admin_m_input01_open(); ?>
                                    <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('thanks_button_url', [
                                        'type' => 'text',
                                        'id' => 'thanks_button_url',
                                        'name' => 'thanks_button_url',
                                        'value' => (string) ($form['thanks_button_url'] ?? '/'),
                                        'placeholder' => '/ または https://example.com/',
                                    ])); ?>
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
