<?php /** フォーム編集:「基本」タブパネル */ ?>
        <div data-target="c-formEditTabs01__contents" data-tab-panel="basic" class="area is-contents" role="tabpanel" aria-label="基本">
<?php admin_c_form_block01_open(); ?>
            <div class="block is-title">
                <?php admin_m_large_title01('基本設定'); ?>
            </div>
            <div class="block is-field">
                <?php admin_m_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('タイトル') . ' <span data-module="m-required01" data-type="required">必須</span>', 'title'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <input type="text" id="title" name="title" value="<?= e($form['title']) ?>" required<?= admin_m_bold_title01_aria_labelledby_attr('title') ?>>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_m_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('スラッグ（URL用）') . ' <span data-module="m-required01" data-type="required">必須</span>', 'slug'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <input type="text" id="slug" name="slug" value="<?= e($form['slug']) ?>" required<?= admin_m_bold_title01_aria_labelledby_attr('slug') ?>>
                                <?php admin_m_input01_close(); ?>
                            </div>
                            <div class="area is-help">
                                <p class="text is-help"><a data-target="p-formEdit01__slugLink" href="<?= e(url('/f/' . $slugPreviewValue)) ?>" target="_blank" rel="noopener"><code><?= e(url('/f/')) ?></code><strong data-target="p-formEdit01__slugPreview"><?= e($slugPreviewValue) ?></strong></a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_m_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('公開状態'), 'status'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <select id="status" name="status"<?= admin_m_bold_title01_aria_labelledby_attr('status') ?>>
                                        <option value="draft"    <?= $form['status'] === 'draft' ? 'selected' : '' ?>>下書き</option>
                                        <option value="active"   <?= $form['status'] === 'active' ? 'selected' : '' ?>>公開中</option>
                                        <option value="archived" <?= $form['status'] === 'archived' ? 'selected' : '' ?>>停止</option>
                                    </select>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                        </div>
                    </div>
                </div>
<?php admin_c_form_block01_open(); ?>
            <div class="block is-title">
                <?php admin_m_large_title01('共通設定'); ?>
            </div>
            <div class="block is-field">
                <?php admin_m_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('head'), 'meta_html'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <textarea id="meta_html" name="meta_html" rows="8" placeholder="<meta name=&quot;description&quot; content=&quot;フォームの概要&quot;>"<?= admin_m_bold_title01_aria_labelledby_attr('meta_html') ?>><?= e($form['meta_html'] ?? '') ?></textarea>
                                <?php admin_m_input01_close(); ?>
                            </div>
                            <div class="area is-help">
                                <p class="text is-help">`&lt;meta ...&gt;` など、head に出したいHTMLをそのまま入力できます。</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_m_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('css'), 'custom_css'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <textarea id="custom_css" name="custom_css" rows="8" placeholder=".p-formShow01 {\n  background: #fff;\n}"<?= admin_m_bold_title01_aria_labelledby_attr('custom_css') ?>><?= e($form['custom_css'] ?? '') ?></textarea>
                                <?php admin_m_input01_close(); ?>
                            </div>
                            <div class="area is-help">
                                <p class="text is-help">公開フォーム専用の CSS をそのまま入力できます。</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_m_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('js'), 'custom_js'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <textarea id="custom_js" name="custom_js" rows="8" placeholder="document.addEventListener('DOMContentLoaded', function () {\n  console.log('form loaded');\n});"<?= admin_m_bold_title01_aria_labelledby_attr('custom_js') ?>><?= e($form['custom_js'] ?? '') ?></textarea>
                                <?php admin_m_input01_close(); ?>
                            </div>
                            <div class="area is-help">
                                <p class="text is-help">公開フォーム専用の JavaScript をそのまま入力できます。</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                        </div>
                    </div>
                </div>
        </div>
