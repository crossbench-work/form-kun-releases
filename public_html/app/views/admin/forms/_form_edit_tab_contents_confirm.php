<?php /** フォーム編集:「確認画面」タブパネル */ ?>
        <div data-target="c-formEditTabs01__contents" data-tab-panel="confirm" class="area is-contents" role="tabpanel" aria-label="確認画面" hidden>
<?php admin_c_form_block01_open(); ?>
            <div class="block is-title">
                <?php admin_m_large_title01('表示設定'); ?>
                <?php admin_m_btn01_open(); ?>
                    <button
                        type="submit"
                        class="btn"
                        formaction="<?= e(base_path('/admin/forms/preview-confirm')) ?>"
                        formmethod="post"
                        formtarget="_blank"
                        formnovalidate
                    >プレビュー</button>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01" for="confirm_page_title">タイトル</label>
                                <label class="label is-choice">
                                    <input type="checkbox" name="show_confirm_page_title" value="1" <?= !empty($form['show_confirm_page_title']) ? 'checked' : '' ?>>
                                    表示する
                                </label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" id="confirm_page_title" name="confirm_page_title" value="<?= e($form['confirm_page_title'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $renderDescriptionEditor('confirm_blocks', $confirmBlocks, 'simple'); ?>
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
                                <label class="label" data-module="m-boldTitle01" for="submit_button_text">送信ボタンテキスト</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" id="submit_button_text" name="submit_button_text" value="<?= e($form['submit_button_text'] ?? '送信する') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01" for="back_button_text">戻るボタンテキスト</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" id="back_button_text" name="back_button_text" value="<?= e($form['back_button_text'] ?? '入力に戻る') ?>">
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
