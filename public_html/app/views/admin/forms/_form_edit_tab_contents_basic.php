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
                                    <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('title', [
                                        'type' => 'text',
                                        'id' => 'title',
                                        'name' => 'title',
                                        'value' => (string) $form['title'],
                                        'required' => true,
                                    ])); ?>
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
                                    <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('slug', [
                                        'type' => 'text',
                                        'id' => 'slug',
                                        'name' => 'slug',
                                        'value' => (string) $form['slug'],
                                        'required' => true,
                                    ])); ?>
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
                                    <?php admin_m_input01_select_open(admin_m_input01_attrs_with_bound_label('status', [
                                        'id' => 'status',
                                        'name' => 'status',
                                    ])); ?>
                                    <?php admin_m_input01_option('draft', '下書き', $form['status'] === 'draft'); ?>
                                    <?php admin_m_input01_option('active', '公開中', $form['status'] === 'active'); ?>
                                    <?php admin_m_input01_option('archived', '停止', $form['status'] === 'archived'); ?>
                                    <?php admin_m_input01_select_close(); ?>
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
                                    <?php admin_m_input01_textarea(admin_m_input01_attrs_with_bound_label('meta_html', [
                                        'id' => 'meta_html',
                                        'name' => 'meta_html',
                                        'rows' => 8,
                                        'placeholder' => '<meta name="description" content="フォームの概要">',
                                    ]), (string) ($form['meta_html'] ?? '')); ?>
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
                                    <?php admin_m_input01_textarea(admin_m_input01_attrs_with_bound_label('custom_css', [
                                        'id' => 'custom_css',
                                        'name' => 'custom_css',
                                        'rows' => 8,
                                        'placeholder' => ".p-formShow01 {\n  background: #fff;\n}",
                                    ]), (string) ($form['custom_css'] ?? '')); ?>
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
                                    <?php admin_m_input01_textarea(admin_m_input01_attrs_with_bound_label('custom_js', [
                                        'id' => 'custom_js',
                                        'name' => 'custom_js',
                                        'rows' => 8,
                                        'placeholder' => "document.addEventListener('DOMContentLoaded', function () {\n  console.log('form loaded');\n});",
                                    ]), (string) ($form['custom_js'] ?? '')); ?>
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
