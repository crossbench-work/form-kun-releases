<?php /** フォーム編集:「基本」タブパネル */ ?>
        <div data-target="c-formEditTabs01__contents" data-tab-panel="basic" class="area is-contents" role="tabpanel" aria-label="基本">
<?php admin_c_form_block01_open(); ?>
            <div class="block is-title">
                <?php admin_m_large_title01('基本設定'); ?>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01" for="title">タイトル <span data-module="m-required01" data-type="required">必須</span></label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" id="title" name="title" value="<?= e($form['title']) ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01" for="slug">スラッグ（URL用） <span data-module="m-required01" data-type="required">必須</span></label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" id="slug" name="slug" value="<?= e($form['slug']) ?>" required>
                                </div>
                            </div>
                            <div class="area is-help">
                                <p class="text is-help"><a data-target="p-formEdit01__slugLink" href="<?= e(url('/f/' . $slugPreviewValue)) ?>" target="_blank" rel="noopener"><code><?= e(url('/f/')) ?></code><strong data-target="p-formEdit01__slugPreview"><?= e($slugPreviewValue) ?></strong></a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01" for="status">公開状態</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <select id="status" name="status">
                                        <option value="draft"    <?= $form['status'] === 'draft' ? 'selected' : '' ?>>下書き</option>
                                        <option value="active"   <?= $form['status'] === 'active' ? 'selected' : '' ?>>公開中</option>
                                        <option value="archived" <?= $form['status'] === 'archived' ? 'selected' : '' ?>>停止</option>
                                    </select>
                                </div>
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
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01" for="meta_html">head</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <textarea id="meta_html" name="meta_html" rows="8" placeholder="<meta name=&quot;description&quot; content=&quot;フォームの概要&quot;>"><?= e($form['meta_html'] ?? '') ?></textarea>
                                </div>
                            </div>
                            <div class="area is-help">
                                <p class="text is-help">`&lt;meta ...&gt;` など、head に出したいHTMLをそのまま入力できます。</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01" for="custom_css">css</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <textarea id="custom_css" name="custom_css" rows="8" placeholder=".p-formShow01 {\n  background: #fff;\n}"><?= e($form['custom_css'] ?? '') ?></textarea>
                                </div>
                            </div>
                            <div class="area is-help">
                                <p class="text is-help">公開フォーム専用の CSS をそのまま入力できます。</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01" for="custom_js">js</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <textarea id="custom_js" name="custom_js" rows="8" placeholder="document.addEventListener('DOMContentLoaded', function () {\n  console.log('form loaded');\n});"><?= e($form['custom_js'] ?? '') ?></textarea>
                                </div>
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
