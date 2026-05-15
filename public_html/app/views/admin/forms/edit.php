<?php
$FB_REGEX_CUSTOM = '__custom__';
$FB_REGEX_CHECKBOX_LIMIT = '__checkbox_limit__';
$regexPresetChoices = [
    '' => 'なし',
    '/\A[^\s@]+@[^\s@]+\.[^\s@]+\z/u' => 'メールアドレス',
    '/\A(?:\+?\d[\d\-() ]*)\z/u' => '電話番号',
    '/\A[0-9]+\z/u' => '半角数字のみ',
    '/\A[a-zA-Z0-9]+\z/u' => '半角英数字のみ',
    '/\A[ぁ-んー]+\z/u' => 'ひらがなのみ',
    '/\A[ァ-ヶー　]+\z/u' => 'カタカナのみ（全角スペース可）',
    '/\A[a-zA-Z]+\z/u' => '半角英字のみ',
    $FB_REGEX_CUSTOM => 'カスタム（正規表現を入力）',
];
$breadcrumbTitle = trim((string) ($form['title'] ?? '')) !== '' ? (string) $form['title'] : '（無題）';
$slugPreviewValue = trim((string) ($form['slug'] ?? '')) !== '' ? (string) $form['slug'] : 'your-slug';
$descriptionBlocks = $form['description_blocks'] ?? [['id' => 'description-0', 'label' => '', 'html' => '', 'position' => 0, 'visible' => 1, 'is_html' => 0]];
$confirmBlocks = $form['confirm_blocks'] ?? [['id' => 'confirm-0', 'label' => '', 'html' => '', 'position' => 0, 'visible' => 1, 'is_html' => 0]];
$thanksBlocks = $form['thanks_blocks'] ?? [['id' => 'thanks-0', 'label' => '', 'html' => '', 'position' => 0, 'visible' => 1, 'is_html' => 0]];
$renderDescriptionEditor = static function (string $namePrefix, array $blocks, string $positionMode): void {
    ?>
    <div class="block is-field">
        <div data-module="c-inputSet01">
            <div class="wrapper">
                <div class="block is-contents">
                    <div class="block is-title">
                        <label class="label" data-module="m-boldTitle01">説明文</label>
                    </div>
                    <div class="block is-field">
                        <div data-module="c-descriptionEditor01" data-description-editor data-name-prefix="<?= e($namePrefix) ?>" data-position-mode="<?= e($positionMode) ?>">
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
                                                ?>
                                                <div class="c-formEditor__item is-collapsed<?= empty($block['visible']) ? ' is-editor-item-hidden' : '' ?>" data-description-block data-index="<?= $i ?>">
                                                    <input type="hidden" name="<?= e($namePrefix) ?>[<?= $i ?>][id]" value="<?= e($blockId) ?>" data-name="id">
                                                    <div class="wrapper">
                                                        <div class="block is-header">
                                                            <div class="c-formEditor__itemHeader">
                                                                <div class="wrapper">
                                                                    <div class="block is-contents">
                                                                        <div class="area is-move">
                                                                            <div class="box is-handle ui-sortable-handle" title="ドラッグして並べ替え">
                                                                                <p data-module="m-dragHandle01" data-target="c-descriptionEditor__dragHandle">
                                                                                    <svg width="14" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="currentColor"><circle cx="9" cy="6" r="2"/><circle cx="15" cy="6" r="2"/><circle cx="9" cy="12" r="2"/><circle cx="15" cy="12" r="2"/><circle cx="9" cy="18" r="2"/><circle cx="15" cy="18" r="2"/></svg>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="area is-summary">
                                                                            <div class="c-descriptionEditorSummary01">
                                                                                <div class="wrapper">
                                                                                    <div class="block is-contents">
                                                                                        <p class="label" data-target="c-descriptionEditor__summaryLabel"<?= $labelValue === '' ? ' hidden' : '' ?>><?= $labelValue !== '' ? e($labelValue) : '' ?></p>
                                                                                        <p class="position" data-target="c-descriptionEditor__summaryPosition"><?= e(((int) ($block['position'] ?? 0)) === 0 ? '先頭' : '末尾') ?></p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="area is-delete">
                                                                            <label class="c-descriptionEditorSummary01__visibility" title="<?= !empty($block['visible']) ? '表示中' : '非表示' ?>">
                                                                                <input type="checkbox" name="<?= e($namePrefix) ?>[<?= $i ?>][visible]" value="1" data-name="visible" <?= !empty($block['visible']) ? 'checked' : '' ?>>
                                                                                <span aria-hidden="true">
                                                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                                                                                        <circle cx="12" cy="12" r="3"/>
                                                                                        <path class="is-hiddenLine" stroke-linecap="round" stroke-linejoin="round" d="M4 20L20 4"/>
                                                                                    </svg>
                                                                                </span>
                                                                            </label>
                                                                            <button type="button" data-module="m-deleteBtn01" data-target="fb-description-remove" aria-label="削除" title="削除">
                                                                                <svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/>
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2"/>
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6"/>
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/>
                                                                                </svg>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="block is-body">
                                                            <div class="c-formEditor__itemBody">
                                                                <div class="wrapper">
                                                                    <div class="block is-contents">
                                                                        <div class="block is-field">
                                                                            <div data-module="c-inputSet01">
                                                                                <div class="wrapper">
                                                                                    <div class="block is-contents">
                                                                                        <div class="block is-title">
                                                                                            <label class="label" data-module="m-boldTitle01" for="<?= e($labelInputId) ?>">ラベル</label>
                                                                                        </div>
                                                                                        <div class="block is-field">
                                                                                            <div data-module="m-input01">
                                                                                                <input type="text" id="<?= e($labelInputId) ?>" name="<?= e($namePrefix) ?>[<?= $i ?>][label]" value="<?= e($labelValue) ?>" data-name="label" data-target="c-descriptionEditor__labelInput">
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
                                                                                            <label class="label" data-module="m-boldTitle01" for="<?= e($positionInputId) ?>">表示位置</label>
                                                                                        </div>
                                                                                        <div class="block is-field">
                                                                                            <div data-module="m-input01">
                                                                                                <select id="<?= e($positionInputId) ?>" name="<?= e($namePrefix) ?>[<?= $i ?>][position]" class="fb-description-position" data-name="position" data-value="<?= e((string) ($block['position'] ?? 0)) ?>" aria-label="説明文の表示位置"></select>
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
                                                                                            <label class="label" data-module="m-boldTitle01" for="<?= e($htmlInputId) ?>">説明文</label>
                                                                                        </div>
                                                                                        <div class="block is-field">
                                                                                            <div data-module="c-htmlInputEditor01" data-html-toggle-editor>
                                                                                                <div class="wrapper">
                                                                                                    <div class="block is-editor">
                                                                                                        <div data-module="m-input01">
                                                                                                            <textarea id="<?= e($htmlInputId) ?>" name="<?= e($namePrefix) ?>[<?= $i ?>][html]" rows="6" data-name="html" data-target="c-htmlInputEditor01__textarea"><?= e((string) ($block['html'] ?? '')) ?></textarea>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="block is-toolbar">
                                                                                                        <label class="label is-choice c-htmlInputEditor01__toggle">
                                                                                                            <input type="checkbox" name="<?= e($namePrefix) ?>[<?= $i ?>][is_html]" value="1" data-name="is_html" data-target="c-htmlInputEditor01__toggle" <?= !empty($block['is_html']) ? 'checked' : '' ?>>
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
                                                </div>
                                            <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="block is-toolbar">
                                    <div class="area is-actions">
                                        <div data-module="m-btn01">
                                            <button type="button" data-target="m-formBuilder01__add" class="btn fb-description-add">＋ 項目を追加</button>
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
    <?php
};
?>

<div class="c-breadcrumb01">
    <div class="wrapper">
        <div class="block is-contents">
            <nav aria-label="パンくず">
                <ol>
                    <li><a href="<?= e(base_path('/admin/forms')) ?>">フォーム</a></li>
                    <li aria-current="page"><span data-target="p-formEdit01__breadcrumbCurrent"><?= e($breadcrumbTitle) ?></span></li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<form method="post" action="<?= e($isNew ? base_path('/admin/forms') : base_path('/admin/forms/' . (int)$form['id'])) ?>" class="p-formEdit01">
    <?= \Core\Csrf::field() ?>
    <div class="wrapper">
        <div class="block is-contents">
            <?php if (!empty($errors)): ?>
                <div class="m-alert01 is-danger">
                    <ul><?php foreach ($errors as $msg): ?><li><?= e($msg) ?></li><?php endforeach; ?></ul>
                </div>
            <?php endif; ?>

            <div data-module="c-formEditTabs01">
                <div class="wrapper">
                    <div class="block is-tabs" role="tablist" aria-label="フォーム設定のセクション">
                        <div data-module="m-formEditTab01" data-target="c-formEditTabs01__tab" data-tab-panel="basic" role="tab" aria-selected="true" tabindex="0">基本</div>
                        <div data-module="m-formEditTab01" data-target="c-formEditTabs01__tab" data-tab-panel="fields" role="tab" aria-selected="false" tabindex="-1">入力画面</div>
                        <div data-module="m-formEditTab01" data-target="c-formEditTabs01__tab" data-tab-panel="confirm" role="tab" aria-selected="false" tabindex="-1">確認画面</div>
                        <div data-module="m-formEditTab01" data-target="c-formEditTabs01__tab" data-tab-panel="thanks" role="tab" aria-selected="false" tabindex="-1">完了画面</div>
                        <div data-module="m-formEditTab01" data-target="c-formEditTabs01__tab" data-tab-panel="mail" role="tab" aria-selected="false" tabindex="-1">通知</div>
                    </div>

                    <div class="block is-contents">
        <div data-target="c-formEditTabs01__contents" data-tab-panel="basic" class="area is-contents" role="tabpanel" aria-label="基本">
    <div data-module="c-formBlock01">
                    <div class="wrapper">
                        <div class="block is-contents">
            <div class="block is-title">
                <h2 data-module="m-largeTitle01">基本設定</h2>
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
    <div data-module="c-formBlock01">
                    <div class="wrapper">
                        <div class="block is-contents">
            <div class="block is-title">
                <h2 data-module="m-largeTitle01">共通設定</h2>
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

        <div data-target="c-formEditTabs01__contents" data-tab-panel="fields" class="area is-contents" role="tabpanel" aria-label="入力画面" hidden>
    <div data-module="c-formBlock01">
                    <div class="wrapper">
                        <div class="block is-contents">
            <div class="block is-title">
                <h2 data-module="m-largeTitle01">表示設定</h2>
                <div data-module="m-btn01">
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
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01" for="input_page_title">タイトル</label>
                                <label class="label is-choice">
                                    <input type="checkbox" name="show_input_page_title" value="1" <?= !empty($form['show_input_page_title']) ? 'checked' : '' ?>>
                                    表示する
                                </label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" id="input_page_title" name="input_page_title" value="<?= e($form['input_page_title'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $renderDescriptionEditor('description_blocks', $descriptionBlocks, 'input'); ?>
                        </div>
                    </div>
                </div>
    <div data-module="c-formBlock01">
                    <div class="wrapper">
                        <div class="block is-contents" data-module="m-formBuilder01">
            <div class="block is-title">
                <h2 data-module="m-largeTitle01">項目設定</h2>
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
                <div data-module="m-btn01">
                    <button type="button" data-target="m-formBuilder01__add" class="btn">＋ 項目を追加</button>
                </div>
            </div>
            </div>
                        </div>
                    </div>
                </div>
    <div data-module="c-formBlock01">
                    <div class="wrapper">
                        <div class="block is-contents">
            <div class="block is-title">
                <h2 data-module="m-largeTitle01">ボタン設定</h2>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01" for="confirm_button_text">ボタンテキスト</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" id="confirm_button_text" name="confirm_button_text" value="<?= e($form['confirm_button_text'] ?? '入力内容を確認する') ?>">
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

        <div data-target="c-formEditTabs01__contents" data-tab-panel="confirm" class="area is-contents" role="tabpanel" aria-label="確認画面" hidden>
    <div data-module="c-formBlock01">
                    <div class="wrapper">
                        <div class="block is-contents">
            <div class="block is-title">
                <h2 data-module="m-largeTitle01">表示設定</h2>
                <div data-module="m-btn01">
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
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
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
    <div data-module="c-formBlock01">
                    <div class="wrapper">
                        <div class="block is-contents">
            <div class="block is-title">
                <h2 data-module="m-largeTitle01">ボタン設定</h2>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
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
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
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

        <div data-target="c-formEditTabs01__contents" data-tab-panel="thanks" class="area is-contents" role="tabpanel" aria-label="完了画面" hidden>
    <div data-module="c-formBlock01">
                    <div class="wrapper">
                        <div class="block is-contents">
            <div class="block is-title">
                <h2 data-module="m-largeTitle01">表示設定</h2>
                <div data-module="m-btn01">
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
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01" for="thanks_page_title">タイトル</label>
                                <label class="label is-choice">
                                    <input type="checkbox" name="show_thanks_page_title" value="1" <?= !empty($form['show_thanks_page_title']) ? 'checked' : '' ?>>
                                    表示する
                                </label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" id="thanks_page_title" name="thanks_page_title" value="<?= e($form['thanks_page_title'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $renderDescriptionEditor('thanks_blocks', $thanksBlocks, 'simple'); ?>
                        </div>
                    </div>
                </div>
    <div data-module="c-formBlock01">
                    <div class="wrapper">
                        <div class="block is-contents">
            <div class="block is-title">
                <h2 data-module="m-largeTitle01">ボタン設定</h2>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01" for="thanks_button_text">ボタンテキスト</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" id="thanks_button_text" name="thanks_button_text" value="<?= e($form['thanks_button_text'] ?? 'トップへ戻る') ?>">
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
                                <label class="label" data-module="m-boldTitle01" for="thanks_button_url">ボタンURL</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" id="thanks_button_url" name="thanks_button_url" value="<?= e($form['thanks_button_url'] ?? '/') ?>" placeholder="/ または https://example.com/">
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

        <div data-target="c-formEditTabs01__contents" data-tab-panel="mail" class="area is-contents" role="tabpanel" aria-label="通知" hidden>
    <div data-module="c-formBlock01">
                    <div class="wrapper">
                        <div class="block is-contents">
            <div class="block is-title">
                <h2 data-module="m-largeTitle01">通知設定</h2>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">送信方式</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <select name="mail_transport">
                                        <option value="mail" <?= ($form['mail_transport'] ?? 'mail') === 'mail' ? 'selected' : '' ?>>PHP mail() / mb_send_mail()</option>
                                        <option value="smtp" <?= ($form['mail_transport'] ?? '') === 'smtp' ? 'selected' : '' ?>>SMTP</option>
                                    </select>
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
                                <label class="label" data-module="m-boldTitle01">差出人メールアドレス</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="email" name="mail_from_email" value="<?= e($form['mail_from_email'] ?? '') ?>" placeholder="noreply@example.com">
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
                                <label class="label" data-module="m-boldTitle01">差出人名</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" name="mail_from_name" value="<?= e($form['mail_from_name'] ?? '') ?>">
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
                                <label class="label" data-module="m-boldTitle01">SMTPホスト</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" name="mail_smtp_host" value="<?= e($form['mail_smtp_host'] ?? '') ?>">
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
                                <label class="label" data-module="m-boldTitle01">SMTPポート</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="number" name="mail_smtp_port" value="<?= e($form['mail_smtp_port'] ?? '25') ?>" min="1" max="65535">
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
                                <label class="label" data-module="m-boldTitle01">暗号化方式</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <select name="mail_smtp_secure">
                                        <option value="" <?= ($form['mail_smtp_secure'] ?? '') === '' ? 'selected' : '' ?>>なし</option>
                                        <option value="ssl" <?= ($form['mail_smtp_secure'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                                        <option value="tls" <?= ($form['mail_smtp_secure'] ?? '') === 'tls' ? 'selected' : '' ?>>STARTTLS</option>
                                    </select>
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
                                <label class="label" data-module="m-boldTitle01">SMTPユーザー名</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" name="mail_smtp_username" value="<?= e($form['mail_smtp_username'] ?? '') ?>" autocomplete="off">
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
                                <label class="label" data-module="m-boldTitle01">SMTPパスワード</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="password" name="mail_smtp_password" value="" autocomplete="new-password">
                                </div>
                                <p class="text is-help">空欄のまま保存すると現在の値を保持します。<?= !empty($form['mail_smtp_password_configured']) ? '現在は設定済みです。' : '現在は未設定です。' ?></p>
                                <?php if (!empty($form['mail_smtp_password_configured'])): ?>
                                    <label class="label is-choice">
                                        <input type="checkbox" name="mail_smtp_clear_password" value="1" <?= !empty($form['mail_smtp_clear_password']) ? 'checked' : '' ?>>
                                        保存済みのSMTPパスワードを削除する
                                    </label>
                                <?php endif; ?>
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
                                <label class="label" data-module="m-boldTitle01">EHLO ホスト名</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" name="mail_smtp_ehlo_host" value="<?= e($form['mail_smtp_ehlo_host'] ?? '') ?>" placeholder="localhost">
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
                                <label class="label" data-module="m-boldTitle01">タイムアウト（秒）</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="number" name="mail_smtp_timeout" value="<?= e($form['mail_smtp_timeout'] ?? '10') ?>" min="1" max="300">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <label class="label is-choice">
                    <input type="checkbox" name="mail_smtp_verify_peer" value="1" <?= !empty($form['mail_smtp_verify_peer']) ? 'checked' : '' ?>>
                    SSL 証明書を検証する
                </label>
            </div>
            <div class="block is-field">
                <label class="label is-choice">
                    <input type="checkbox" name="mail_smtp_verify_peer_name" value="1" <?= !empty($form['mail_smtp_verify_peer_name']) ? 'checked' : '' ?>>
                    証明書のホスト名を検証する
                </label>
            </div>
            <div class="block is-field">
                <label class="label is-choice">
                    <input type="checkbox" name="mail_smtp_allow_self_signed" value="1" <?= !empty($form['mail_smtp_allow_self_signed']) ? 'checked' : '' ?>>
                    自己署名証明書を許可する
                </label>
                <p class="text is-help">ローカル開発や検証用 SMTP のみで使ってください。</p>
            </div>
                        </div>
                    </div>
                </div>

    <div data-module="c-formBlock01">
                    <div class="wrapper">
                        <div class="block is-contents">
            <div class="block is-title">
                <h2 data-module="m-largeTitle01">管理者通知メール</h2>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">送信先メールアドレス（カンマ区切りで複数可）</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" name="admin_emails" value="<?= e($form['admin_emails']) ?>" placeholder="admin@example.com, sub@example.com">
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
                                <label class="label" data-module="m-boldTitle01">件名</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" name="mail_subject_admin" value="<?= e($form['mail_subject_admin']) ?>" placeholder="[{{__form_title__}}] 新規送信">
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
                                <label class="label" data-module="m-boldTitle01">表示設定</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <textarea name="mail_template_admin" rows="6"><?= e($form['mail_template_admin']) ?></textarea>
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
                                <label class="label" data-module="m-boldTitle01">使用可能な変数</label>
                            </div>
                            <div class="block is-field">
                                <div class="c-copyTagList01">
                                    <div class="c-copyTagList01__items">
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{field_key}}"><code>{{field_key}}</code></button>
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{__all__}}"><code>{{__all__}}</code></button>
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{__form_title__}}"><code>{{__form_title__}}</code></button>
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{__submitted_at__}}"><code>{{__submitted_at__}}</code></button>
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{__ip__}}"><code>{{__ip__}}</code></button>
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

    <div data-module="c-formBlock01">
                    <div class="wrapper">
                        <div class="block is-contents">
            <div class="block is-title">
                <h2 data-module="m-largeTitle01">送信者への自動返信メール</h2>
            </div>
            <div class="block is-field">
                <label class="label is-choice">
                    <input type="checkbox" name="reply_enabled" value="1" <?= !empty($form['reply_enabled']) ? 'checked' : '' ?>>
                    自動返信を有効にする
                </label>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">送信先のフィールドキー（メール型項目）</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" name="reply_from_field_key" value="<?= e($form['reply_from_field_key']) ?>" placeholder="メールアドレス項目のフィールドキー（UUID または任意のキー）">
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
                                <label class="label" data-module="m-boldTitle01">件名</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" name="reply_subject" value="<?= e($form['reply_subject']) ?>">
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
                                <label class="label" data-module="m-boldTitle01">表示設定</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <textarea name="reply_template" rows="6"><?= e($form['reply_template']) ?></textarea>
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
                                <label class="label" data-module="m-boldTitle01">使用可能な変数</label>
                            </div>
                            <div class="block is-field">
                                <div class="c-copyTagList01">
                                    <div class="c-copyTagList01__items">
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{field_key}}"><code>{{field_key}}</code></button>
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{__all__}}"><code>{{__all__}}</code></button>
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{__form_title__}}"><code>{{__form_title__}}</code></button>
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{__submitted_at__}}"><code>{{__submitted_at__}}</code></button>
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{__ip__}}"><code>{{__ip__}}</code></button>
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

        <div class="block bottom">
            <div data-module="c-btnSet01">
                <div class="wrapper">
                    <div class="block is-contents">
                        <div class="area is-contents">
                            <div data-module="m-btn01">
                                <button type="submit" class="btn is-primary"><?= $isNew ? '作成する' : '保存する' ?></button>
                            </div>
                            <div data-module="m-btn01">
                                <a class="btn" href="<?= e(base_path('/admin/forms')) ?>">キャンセル</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div data-module="c-fieldTypeModal01" data-target="m-formBuilder01__typeModal" hidden>
    <div class="c-fieldTypeModal01__backdrop" data-target="m-formBuilder01__typeModalClose"></div>
    <div class="c-fieldTypeModal01__dialog" role="dialog" aria-modal="true" aria-labelledby="field-type-modal-title">
        <div class="c-fieldTypeModal01__header">
            <h2 id="field-type-modal-title">追加する型を選択</h2>
            <button type="button" class="btn is-sm" data-target="m-formBuilder01__typeModalClose">閉じる</button>
        </div>
        <div class="c-fieldTypeModal01__body">
            <div class="c-fieldTypeModal01__grid">
                <?php foreach (\Models\FormField::TYPE_LABELS() as $typeValue => $typeLabel): ?>
                    <button
                        type="button"
                        class="c-fieldTypeModal01__option"
                        data-target="m-formBuilder01__typeOption"
                        data-field-type="<?= e($typeValue) ?>"
                        data-field-type-label="<?= e($typeLabel) ?>"
                    >
                        <?= e($typeLabel) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<template data-target="m-formBuilder01__descriptionTemplate">
    <div class="c-formEditor__item is-collapsed" data-description-block>
        <input type="hidden" data-name="id" value="">
        <div class="wrapper">
            <div class="block is-header">
                <div class="c-formEditor__itemHeader">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="area is-move">
                                <div class="box is-handle ui-sortable-handle" title="ドラッグして並べ替え">
                                    <p data-module="m-dragHandle01" data-target="c-descriptionEditor__dragHandle">
                                        <svg width="14" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="currentColor"><circle cx="9" cy="6" r="2"/><circle cx="15" cy="6" r="2"/><circle cx="9" cy="12" r="2"/><circle cx="15" cy="12" r="2"/><circle cx="9" cy="18" r="2"/><circle cx="15" cy="18" r="2"/></svg>
                                    </p>
                                </div>
                            </div>
                            <div class="area is-summary">
                                <div class="c-descriptionEditorSummary01">
                                    <div class="wrapper">
                                        <div class="block is-contents">
                                            <p class="label" data-target="c-descriptionEditor__summaryLabel" hidden></p>
                                            <p class="position" data-target="c-descriptionEditor__summaryPosition">先頭</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="area is-delete">
                                <label class="c-descriptionEditorSummary01__visibility" title="表示中">
                                    <input type="checkbox" data-name="visible" value="1" checked>
                                    <span aria-hidden="true">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                            <path class="is-hiddenLine" stroke-linecap="round" stroke-linejoin="round" d="M4 20L20 4"/>
                                        </svg>
                                    </span>
                                </label>
                                <button type="button" data-module="m-deleteBtn01" data-target="fb-description-remove" aria-label="削除" title="削除">
                                    <svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-body">
                <div class="c-formEditor__itemBody">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-field">
                                <div data-module="c-inputSet01">
                                    <div class="wrapper">
                                        <div class="block is-contents">
                                            <div class="block is-title">
                                                <label class="label" data-module="m-boldTitle01">ラベル</label>
                                            </div>
                                            <div class="block is-field">
                                                <div data-module="m-input01">
                                                    <input type="text" data-name="label" data-target="c-descriptionEditor__labelInput">
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
                                                <label class="label" data-module="m-boldTitle01">表示位置</label>
                                            </div>
                                            <div class="block is-field">
                                                <div data-module="m-input01">
                                                    <select class="fb-description-position" data-name="position" data-value="0" aria-label="説明文の表示位置"></select>
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
                                                <label class="label" data-module="m-boldTitle01">説明文</label>
                                            </div>
                                            <div class="block is-field">
                                                <div data-module="c-htmlInputEditor01" data-html-toggle-editor>
                                                    <div class="wrapper">
                                                        <div class="block is-editor">
                                                            <div data-module="m-input01">
                                                                <textarea rows="6" data-name="html" data-target="c-htmlInputEditor01__textarea"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="block is-toolbar">
                                                            <label class="label is-choice c-htmlInputEditor01__toggle">
                                                                <input type="checkbox" data-name="is_html" data-target="c-htmlInputEditor01__toggle" value="1">
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
    </div>
</template>

<template data-target="m-formBuilder01__fieldTemplate">
    <div class="c-formEditor__item is-collapsed" data-field-item="1">
        <div class="wrapper">
            <div class="block is-header">
                    <div class="c-formEditor__itemHeader">
                        <div class="wrapper">
                            <div class="block is-contents">
                                <div class="area is-move">
                                    <div class="box is-handle ui-sortable-handle" title="ドラッグして並べ替え">
                                        <p data-module="m-dragHandle01" data-target="c-formEditor__dragHandle">
                                            <svg width="14" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="currentColor"><circle cx="9" cy="6" r="2"/><circle cx="15" cy="6" r="2"/><circle cx="9" cy="12" r="2"/><circle cx="15" cy="12" r="2"/><circle cx="9" cy="18" r="2"/><circle cx="15" cy="18" r="2"/></svg>
                                        </p>
                                    </div>
                                </div>
                                <div class="area is-summary">
                                    <div class="c-formEditorSummary01">
                                        <div class="wrapper">
                                            <div class="block is-contents">
                                                <p class="index" data-target="c-formEditor__summaryIndex">1</p>
                                                <p class="label" data-target="c-formEditor__summaryLabel"></p>
                                                <p class="type" data-target="c-formEditor__summaryType"><?= e(\Models\FormField::typeLabel('text_multi')) ?></p>
                                                <p class="required" data-target="c-formEditor__summaryRequired" hidden>必須</p>
                                                <p class="regex" data-target="c-formEditor__summaryRegexWrap" hidden><svg width="12" height="12" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg><span data-target="c-formEditor__summaryRegex">なし</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="area is-delete">
                                    <label class="c-formEditorSummary01__visibility" title="表示中">
                                        <input type="checkbox" data-name="visible" value="1" checked>
                                        <span aria-hidden="true">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                                <path class="is-hiddenLine" stroke-linecap="round" stroke-linejoin="round" d="M4 20L20 4"/>
                                            </svg>
                                        </span>
                                    </label>
                                    <button type="button" data-module="m-deleteBtn01" data-target="c-formEditor__remove" aria-label="削除" title="削除">
                                        <svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="block is-body">
                    <div class="c-formEditor__itemBody">
                        <div class="wrapper">
                                <div class="block is-contents">
                                            <div class="block is-title">
                                                <p data-module="m-borderTitle01">基本設定</p>
                                            </div>
                                            <div class="block is-field">
                                                <div class="c-formEditor__field">
                                                    <div class="wrapper">
                                                        <div class="block is-contents">
                                                            <div class="block is-title"><label class="label" data-module="m-labelTitle01">ラベル</label></div>
                                                            <div class="block is-field"><div data-module="m-input01"><input type="text" data-name="label" data-target="c-formEditor__labelInput"></div></div>
                                                        </div>
                                                        <div class="block is-contents c-formEditor__fieldKeyColumn">
                                                            <div class="block is-title"><label class="label" data-module="m-labelTitle01">フィールドキー</label></div>
                                                            <div class="block is-field"><div class="c-formEditor__fieldKey"><div data-module="m-input01"><input type="text" data-name="field_key" data-target="c-formEditor__fieldKeyInput" class="is-locked" readonly spellcheck="false"></div><button type="button" class="btn is-icon" data-target="c-formEditor__fieldKeyLock" aria-label="キーを編集" title="編集する" aria-pressed="true"><svg data-target="c-formEditor__fieldKeyLockedIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg><svg data-target="c-formEditor__fieldKeyOpenIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg></button></div></div>
                                                        </div>
                                                        <input type="hidden" data-name="type" data-target="c-formEditor__type" value="text_multi" data-type-label="<?= e(\Models\FormField::typeLabel('text_multi')) ?>">
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
                                                            <div class="block is-title"><label class="label" data-module="m-labelTitle01">入力チェック</label></div>
                                                            <div class="block is-field"><div class="c-formEditor__validationControls"><div data-module="m-requiredCheck01"><label class="label is-choice"><input type="checkbox" data-name="required" value="1" data-target="c-formEditor__required"><span>必須</span></label></div><div data-target="c-formEditor__regex"><div class="c-formEditor__regexList"><div class="c-formEditor__regexRow"><div data-module="m-input01"><select data-target="c-formEditor__regexKind" aria-label="入力チェックの種類"><?php foreach ($regexPresetChoices as $patVal => $patLabel): ?><option value="<?= e($patVal) ?>"><?= e($patLabel) ?></option><?php endforeach; ?><option value="<?= e($FB_REGEX_CHECKBOX_LIMIT) ?>">選択数を設定する</option></select></div><div class="c-formEditor__regexCustom" style="display:none"><div data-module="m-input01"><input type="text" data-target="c-formEditor__regexCustomInput" placeholder="正規表現" spellcheck="false" autocomplete="off"></div></div><input type="hidden" data-target="c-formEditor__regexValue" value=""></div></div><input type="hidden" data-name="checkbox_limit_enabled" data-target="c-formEditor__checkboxLimitEnabled" value="0"><div class="c-formEditor__checkboxLimit" style="display:none"><div class="wrapper"><div class="block is-contents"><div class="block is-title"><label class="label" data-module="m-labelTitle01">最小選択数</label></div><div class="block is-field"><div data-module="m-input01"><input type="number" min="1" data-name="checkbox_min_selections"></div></div></div><div class="block is-contents"><div class="block is-title"><label class="label" data-module="m-labelTitle01">最大選択数</label></div><div class="block is-field"><div data-module="m-input01"><input type="number" min="1" data-name="checkbox_max_selections"></div></div></div></div></div></div>
                                                        </div>
                                                        <div class="block is-contents" data-target="c-formEditor__minMaxLength"><div class="block is-title"><label class="label" data-module="m-labelTitle01">文字数制限（任意）</label></div><div class="block is-field"><div style="display: flex; gap: 12px;"><div style="flex: 1;"><div data-module="m-input01"><input type="number" min="1" data-name="min_length" placeholder="最小"></div></div><div style="flex: 1;"><div data-module="m-input01"><input type="number" min="1" data-name="max_length" placeholder="最大"></div></div></div></div></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="block is-field" data-target="c-formEditor__options" style="display:none">
                                                <label class="label" data-module="m-labelTitle01">選択肢（1行に1つ）</label>
                                                <div class="c-formEditor__choiceEditor" data-choice-editor>
                                                    <div class="c-formEditor__choiceList"></div>
                                                    <div class="area is-actions">
                                                        <button type="button" class="btn is-sm" data-target="c-formEditor__choiceAdd">＋ 選択肢を追加</button>
                                                    </div>
                                                    <textarea data-name="options" data-target="c-formEditor__optionsSource" rows="4" hidden></textarea>
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
                                                        <div class="block is-contents" data-target="c-formEditor__placeholder"><div class="block is-title"><label class="label" data-module="m-labelTitle01">プレースホルダ（任意）</label></div><div class="block is-field"><div data-module="m-input01"><input type="text" data-name="placeholder"></div></div></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="block is-field">
                                                <div class="c-formEditor__field" data-target="c-formEditor__textMultiSettings" style="display:none">
                                                    <div class="c-formEditor__textMultiEditor" data-text-multi-editor>
                                                        <div class="c-formEditor__textMultiList">
                                                            <div class="wrapper">
                                                                <div class="block is-contents" data-target="c-formEditor__textMultiListContents">
                                                                    <div class="c-formEditor__textMultiItem" data-text-multi-item>
                                                                        <div class="wrapper">
                                                                            <div class="block is-contents">
                                                                                <div class="c-formEditor__field">
                                                                                    <div class="wrapper">
                                                                                        <div class="block is-contents"><div class="area is-title"><label class="label" data-module="m-labelTitle01">表示</label></div><div class="area is-field"><div data-module="m-input01"><input type="text" data-target="c-formEditor__textMultiLabelInput"></div></div></div>
                                                                                        <div class="block is-contents"><div class="area is-title"><label class="label" data-module="m-labelTitle01">入力例</label></div><div class="area is-field"><div data-module="m-input01"><input type="text" data-target="c-formEditor__textMultiPlaceholderInput"></div></div></div>
                                                                                    </div>
                                                                                </div>
                                                                                <button type="button" data-module="m-deleteBtn01" data-target="c-formEditor__textMultiRemove" aria-label="削除" title="削除"><svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2"/><path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6"/><path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/></svg></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="area is-actions">
                                                            <div data-module="m-btn01">
                                                                <button type="button" data-target="c-formEditor__textMultiAdd" class="btn fb-description-add">＋ 文字を追加</button>
                                                            </div>
                                                        </div>
                                                        <textarea data-name="multi_text_items" data-target="c-formEditor__textMultiSource" hidden>[{"label":"","placeholder":""}]</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="block is-field">
                                                <label class="label" data-module="m-labelTitle01">備考</label>
                                                <div data-module="c-htmlInputEditor01" data-html-toggle-editor>
                                                    <div class="wrapper">
                                                        <div class="block is-editor">
                                                            <div data-module="m-input01"><textarea data-name="help_text" rows="3" data-target="c-htmlInputEditor01__textarea"></textarea></div>
                                                        </div>
                                                        <div class="block is-toolbar">
                                                            <label class="label is-choice c-htmlInputEditor01__toggle">
                                                                <input type="checkbox" data-name="help_text_is_html" data-target="c-htmlInputEditor01__toggle" value="1">
                                                                HTMLで入力する
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                <input type="hidden" data-name="_remove" value="0" data-target="c-formEditor__removeFlag">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
