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
$descriptionBlocks = $form['description_blocks'] ?? [['id' => 'description-0', 'html' => '', 'position' => 0, 'visible' => 1]];
$confirmBlocks = $form['confirm_blocks'] ?? [['id' => 'confirm-0', 'html' => '', 'position' => 0, 'visible' => 1]];
$thanksBlocks = $form['thanks_blocks'] ?? [['id' => 'thanks-0', 'html' => '', 'position' => 0, 'visible' => 1]];
$agreementField = null;
$sortableFields = [];
foreach ($fields as $field) {
    if ($agreementField === null && !empty($field['_is_agreement_field'])) {
        $agreementField = $field;
        continue;
    }
    $sortableFields[] = $field;
}
$renderDescriptionEditor = static function (string $namePrefix, array $blocks, string $positionMode): void {
    ?>
    <div class="box is-field fb-description-editor" data-description-editor data-name-prefix="<?= e($namePrefix) ?>" data-position-mode="<?= e($positionMode) ?>">
        <div class="fb-description-blocks">
            <?php foreach ($blocks as $i => $block): ?>
                <div class="fb-description-block" data-index="<?= $i ?>">
                    <input type="hidden" name="<?= e($namePrefix) ?>[<?= $i ?>][id]" value="<?= e($block['id'] ?? ($namePrefix . '-' . $i)) ?>" data-name="id">
                    <div class="fb-description-block-header">
                        <strong class="fb-description-block-title">説明文</strong>
                        <button type="button" class="btn is-sm is-icon is-danger fb-description-remove" aria-label="削除" title="削除">
                            <svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/>
                            </svg>
                        </button>
                    </div>
                    <div class="fb-row fb-description-block-controls" style="display: flex;">
                        <div class="fb-description-position-wrap">
                            <select name="<?= e($namePrefix) ?>[<?= $i ?>][position]" class="fb-description-position" data-name="position" data-value="<?= e((string) ($block['position'] ?? 0)) ?>" aria-label="説明文"></select>
                        </div>
                        <div class="fb-description-visible-wrap">
                            <label class="label is-choice fb-basic-display-toggle">
                                <input type="checkbox" name="<?= e($namePrefix) ?>[<?= $i ?>][visible]" value="1" data-name="visible" <?= !empty($block['visible']) ? 'checked' : '' ?>>
                                表示する
                            </label>
                        </div>
                    </div>
                    <textarea name="<?= e($namePrefix) ?>[<?= $i ?>][html]" rows="6" data-name="html"><?= e((string) ($block['html'] ?? '')) ?></textarea>
                    <p class="text is-help">HTML を直接入力できます。一部のタグと属性だけが保存されます。</p>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="fb-description-toolbar">
            <div class="area is-actions">
                <button type="button" class="btn is-sm fb-description-add">説明文を追加する</button>
            </div>
        </div>
    </div>
    <?php
};
?>

<?php if (!empty($errors)): ?>
    <div class="m-alert01 is-danger">
        <ul><?php foreach ($errors as $msg): ?><li><?= e($msg) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<nav class="m-breadcrumb01" aria-label="パンくず">
    <ol>
        <li><a href="<?= e(base_path('/admin/forms')) ?>">フォーム</a></li>
        <li aria-current="page"><span id="breadcrumb-current"><?= e($breadcrumbTitle) ?></span></li>
    </ol>
</nav>

<form method="post" action="<?= e($isNew ? base_path('/admin/forms') : base_path('/admin/forms/' . (int)$form['id'])) ?>" class="p-formEdit01">
    <?= \Core\Csrf::field() ?>

    <div class="p-formEditTabs01" data-module="m-formEditTabs01">
        <div class="tab" role="tablist" aria-label="フォーム設定のセクション">
            <button type="button" class="tab-item" role="tab" id="tab-btn-basic" aria-selected="true" aria-controls="tab-panel-basic">基本</button>
            <button type="button" class="tab-item" role="tab" id="tab-btn-fields" aria-selected="false" aria-controls="tab-panel-fields" tabindex="-1">入力画面</button>
            <button type="button" class="tab-item" role="tab" id="tab-btn-confirm" aria-selected="false" aria-controls="tab-panel-confirm" tabindex="-1">確認画面</button>
            <button type="button" class="tab-item" role="tab" id="tab-btn-thanks" aria-selected="false" aria-controls="tab-panel-thanks" tabindex="-1">完了画面</button>
            <button type="button" class="tab-item" role="tab" id="tab-btn-mail" aria-selected="false" aria-controls="tab-panel-mail" tabindex="-1">通知</button>
        </div>

        <div id="tab-panel-basic" class="box is-tabPanel" role="tabpanel" aria-labelledby="tab-btn-basic">
    <section class="c-panel01">
        <div class="componentWrapper">
            <h2>基本設定</h2>
            <div class="box is-field">
                <div class="fb-basic-label-row">
                    <label class="label" for="title">タイトル <span class="is-required">必須</span></label>
                    <label class="label is-choice fb-basic-display-toggle">
                        <input type="checkbox" name="show_title" value="1" <?= !empty($form['show_title']) ? 'checked' : '' ?>>
                        表示する
                    </label>
                </div>
                <input type="text" id="title" name="title" value="<?= e($form['title']) ?>" required>
            </div>
            <div class="box is-field">
                <label class="label" for="slug">スラッグ（URL用） <span class="is-required">必須</span></label>
                <input type="text" id="slug" name="slug" value="<?= e($form['slug']) ?>" required>
                <p class="text is-help"><a id="slug-link" href="<?= e(url('/f/' . $slugPreviewValue)) ?>" target="_blank" rel="noopener"><code><?= e(url('/f/')) ?></code><strong id="slug-preview"><?= e($slugPreviewValue) ?></strong></a></p>
            </div>
            <div class="box is-field">
                <label class="label" for="status">公開状態</label>
                <select id="status" name="status">
                    <option value="draft"    <?= $form['status'] === 'draft' ? 'selected' : '' ?>>下書き</option>
                    <option value="active"   <?= $form['status'] === 'active' ? 'selected' : '' ?>>公開中</option>
                    <option value="archived" <?= $form['status'] === 'archived' ? 'selected' : '' ?>>停止</option>
                </select>
            </div>
        </div>
    </section>
        </div>

        <div id="tab-panel-fields" class="box is-tabPanel" role="tabpanel" aria-labelledby="tab-btn-fields" hidden>
    <section class="c-panel01">
        <div class="componentWrapper">
            <div class="fb-panel-header">
                <h2 id="input-screen-title-preview">表示設定</h2>
                <button
                    type="submit"
                    class="btn is-sm"
                    formaction="<?= e(base_path('/admin/forms/preview-input')) ?>"
                    formmethod="post"
                    formtarget="_blank"
                    formnovalidate
                >プレビュー</button>
            </div>
            <?php $renderDescriptionEditor('description_blocks', $descriptionBlocks, 'input'); ?>
        </div>
    </section>
    <section class="c-panel01" data-module="m-formBuilder01">
        <div class="componentWrapper">
            <h2>項目設定</h2>
            <ul id="fb-fields" class="fb-fields">
            <?php foreach ($sortableFields as $i => $f): ?>
                <?php $idx = $i; $isAgreementField = false; ?>
                <?php include __DIR__ . '/_field_editor.php'; ?>
            <?php endforeach; ?>
            </ul>

            <div class="area is-actions">
                <button type="button" id="fb-add" class="btn">＋ 項目を追加</button>
            </div>
            <?php if ($agreementField !== null): ?>
                <ul class="fb-fields fb-static-field">
                    <?php $idx = count($sortableFields); $f = $agreementField; $isAgreementField = true; ?>
                    <?php include __DIR__ . '/_field_editor.php'; ?>
                </ul>
            <?php endif; ?>
        </div>
    </section>
    <section class="c-panel01">
        <div class="componentWrapper">
            <h2>スパム対策</h2>
            <div class="box is-field">
                <label class="label">
                    <input type="checkbox" name="captcha_enabled" value="1" <?= !empty($form['captcha_enabled']) ? 'checked' : '' ?>>
                    スパム対策（簡易計算CAPTCHA）を有効にする
                </label>
            </div>
        </div>
    </section>
    <section class="c-panel01">
        <div class="componentWrapper">
            <h2>ボタン設定</h2>
            <div class="box is-field">
                <label class="label" for="confirm_button_text">ボタンテキスト</label>
                <input type="text" id="confirm_button_text" name="confirm_button_text" value="<?= e($form['confirm_button_text'] ?? '入力内容を確認する') ?>">
            </div>
        </div>
    </section>
        </div>

        <div id="tab-panel-confirm" class="box is-tabPanel" role="tabpanel" aria-labelledby="tab-btn-confirm" hidden>
    <section class="c-panel01">
        <div class="componentWrapper">
            <div class="fb-panel-header">
                <h2 id="confirm-screen-title-preview">表示設定</h2>
                <button
                    type="submit"
                    class="btn is-sm"
                    formaction="<?= e(base_path('/admin/forms/preview-confirm')) ?>"
                    formmethod="post"
                    formtarget="_blank"
                    formnovalidate
                >プレビュー</button>
            </div>
            <?php $renderDescriptionEditor('confirm_blocks', $confirmBlocks, 'simple'); ?>
        </div>
    </section>
    <section class="c-panel01">
        <div class="componentWrapper">
            <h2>ボタン設定</h2>
            <div class="box is-field">
                <label class="label" for="submit_button_text">送信ボタンテキスト</label>
                <input type="text" id="submit_button_text" name="submit_button_text" value="<?= e($form['submit_button_text'] ?? '送信する') ?>">
            </div>
            <div class="box is-field">
                <label class="label" for="back_button_text">戻るボタンテキスト</label>
                <input type="text" id="back_button_text" name="back_button_text" value="<?= e($form['back_button_text'] ?? '入力に戻る') ?>">
            </div>
        </div>
    </section>
        </div>

        <div id="tab-panel-thanks" class="box is-tabPanel" role="tabpanel" aria-labelledby="tab-btn-thanks" hidden>
    <section class="c-panel01">
        <div class="componentWrapper">
            <div class="fb-panel-header">
                <h2 id="thanks-screen-title-preview">表示設定</h2>
                <button
                    type="submit"
                    class="btn is-sm"
                    formaction="<?= e(base_path('/admin/forms/preview-thanks')) ?>"
                    formmethod="post"
                    formtarget="_blank"
                    formnovalidate
                >プレビュー</button>
            </div>
            <?php $renderDescriptionEditor('thanks_blocks', $thanksBlocks, 'simple'); ?>
        </div>
    </section>
    <section class="c-panel01">
        <div class="componentWrapper">
            <h2>ボタン設定</h2>
            <div class="box is-field">
                <label class="label" for="thanks_button_text">ボタンテキスト</label>
                <input type="text" id="thanks_button_text" name="thanks_button_text" value="<?= e($form['thanks_button_text'] ?? 'トップへ戻る') ?>">
            </div>
            <div class="box is-field">
                <label class="label" for="thanks_button_url">ボタンURL</label>
                <input type="text" id="thanks_button_url" name="thanks_button_url" value="<?= e($form['thanks_button_url'] ?? '/') ?>" placeholder="/ または https://example.com/">
            </div>
        </div>
    </section>
        </div>

        <div id="tab-panel-mail" class="box is-tabPanel" role="tabpanel" aria-labelledby="tab-btn-mail" hidden>
    <section class="c-panel01">
        <div class="componentWrapper">
            <h2>管理者通知メール</h2>
            <div class="box is-field">
                <label class="label">送信先メールアドレス（カンマ区切りで複数可）</label>
                <input type="text" name="admin_emails" value="<?= e($form['admin_emails']) ?>" placeholder="admin@example.com, sub@example.com">
            </div>
            <div class="box is-field">
                <label class="label">件名</label>
                <input type="text" name="mail_subject_admin" value="<?= e($form['mail_subject_admin']) ?>" placeholder="[{{__form_title__}}] 新規送信">
            </div>
            <div class="box is-field">
                <label class="label">表示設定</label>
                <textarea name="mail_template_admin" rows="6"><?= e($form['mail_template_admin']) ?></textarea>
                <p class="text is-help">使用可能な変数: <code>{{field_key}}</code> / <code>{{__all__}}</code> / <code>{{__form_title__}}</code> / <code>{{__submitted_at__}}</code> / <code>{{__ip__}}</code></p>
            </div>
        </div>
    </section>

    <section class="c-panel01">
        <div class="componentWrapper">
            <h2>送信者への自動返信メール</h2>
            <div class="box is-field">
                <label class="label">
                    <input type="checkbox" name="reply_enabled" value="1" <?= !empty($form['reply_enabled']) ? 'checked' : '' ?>>
                    自動返信を有効にする
                </label>
            </div>
            <div class="box is-field">
                <label class="label">送信先のフィールドキー（メール型項目）</label>
                <input type="text" name="reply_from_field_key" value="<?= e($form['reply_from_field_key']) ?>" placeholder="メール項目のフィールドキー（UUID または任意のキー）">
            </div>
            <div class="box is-field">
                <label class="label">件名</label>
                <input type="text" name="reply_subject" value="<?= e($form['reply_subject']) ?>">
            </div>
            <div class="box is-field">
                <label class="label">表示設定</label>
                <textarea name="reply_template" rows="6"><?= e($form['reply_template']) ?></textarea>
            </div>
        </div>
    </section>
        </div>

    </div>

    <div class="area is-actions">
        <button type="submit" class="btn is-primary"><?= $isNew ? '作成する' : '保存する' ?></button>
        <a class="btn" href="<?= e(base_path('/admin/forms')) ?>">キャンセル</a>
    </div>
</form>

<template id="fb-description-template">
    <div class="fb-description-block">
        <input type="hidden" data-name="id" value="">
        <div class="fb-description-block-header">
            <strong class="fb-description-block-title">説明文</strong>
            <button type="button" class="btn is-sm is-icon is-danger fb-description-remove" aria-label="削除" title="削除">
                <svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/>
                </svg>
            </button>
        </div>
        <div class="fb-row fb-description-block-controls" style="display: flex;">
            <div class="fb-description-position-wrap">
                <select class="fb-description-position" data-name="position" data-value="0" aria-label="説明文"></select>
            </div>
            <div class="fb-description-visible-wrap">
                <label class="label is-choice fb-basic-display-toggle">
                    <input type="checkbox" data-name="visible" value="1" checked>
                    表示する
                </label>
            </div>
        </div>
        <textarea rows="6" data-name="html"></textarea>
        <p class="text is-help">HTML を直接入力できます。一部のタグと属性だけが保存されます。</p>
    </div>
</template>

<template id="fb-template">
    <li class="fb-field is-collapsed" data-field-item="1">
        <div class="fb-field-header">
            <span class="fb-drag-handle" title="ドラッグして並べ替え">
                <svg width="14" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="currentColor"><circle cx="9" cy="6" r="2"/><circle cx="15" cy="6" r="2"/><circle cx="9" cy="12" r="2"/><circle cx="15" cy="12" r="2"/><circle cx="9" cy="18" r="2"/><circle cx="15" cy="18" r="2"/></svg>
            </span>
            <div class="fb-header-actions">
                <button type="button" class="btn is-sm fb-up" aria-label="上へ移動" title="上へ移動">↑</button>
                <button type="button" class="btn is-sm fb-down" aria-label="下へ移動" title="下へ移動">↓</button>
            </div>
            <div class="fb-field-summary">
                <span class="fb-field-summary-main">
                    <span class="fb-num">1</span>
                    · <strong class="fb-summary-label"></strong>
                    · <code class="fb-summary-type"><?= e(\Models\FormField::typeLabel('text')) ?></code>
                    · <span class="fb-summary-badge fb-summary-required">任意</span>
                    · <span class="fb-summary-badge fb-summary-regex">入力チェック: <span class="fb-summary-regex-text">なし</span></span>
                </span>
            </div>
            <div class="fb-header-delete">
                <button type="button" class="btn is-sm is-danger fb-remove" aria-label="削除" title="削除">
                    <svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="fb-field-body">
        <div class="fb-field-section">
            <h3 class="fb-field-section-title">基本設定</h3>
            <div class="fb-row">
                <div><label>ラベル</label><input type="text" data-name="label" class="fb-input-label"></div>
                <div class="fb-field-key-col">
                    <label>フィールドキー</label>
                    <div class="fb-field-key-row">
                        <input type="text" data-name="field_key" class="fb-field-key-input is-locked" readonly spellcheck="false">
                        <button type="button" class="btn is-icon fb-field-key-lock" aria-label="キーを編集" title="編集する" aria-pressed="true">
                            <svg class="fb-icon-lock-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <svg class="fb-icon-lock-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label>型</label>
                    <select data-name="type" class="fb-type">
                        <?php foreach (\Models\FormField::TYPE_LABELS() as $tVal => $tLabel): ?>
                            <option value="<?= e($tVal) ?>"><?= e($tLabel) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="fb-field-section">
            <h3 class="fb-field-section-title">入力設定</h3>
            <div class="fb-row">
                <div class="fb-validation-wrap">
                    <label>必須</label>
                    <div class="fb-validation-controls">
                        <div class="fb-required-wrap">
                            <label class="label is-choice"><input type="checkbox" data-name="required" value="1" class="fb-required-toggle"><span>必須にする</span></label>
                        </div>
                        <div class="fb-regex-wrap" style="display:none">
                            <div class="fb-regex-list">
                                <div class="fb-regex-row">
                                    <select class="fb-regex-kind" aria-label="入力チェックの種類">
                                        <?php foreach ($regexPresetChoices as $patVal => $patLabel): ?>
                                            <option value="<?= e($patVal) ?>"><?= e($patLabel) ?></option>
                                        <?php endforeach; ?>
                                        <option value="<?= e($FB_REGEX_CHECKBOX_LIMIT) ?>">選択数を設定する</option>
                                    </select>
                                    <div class="fb-regex-custom-wrap" style="display:none">
                                        <input type="text" class="fb-regex-custom" placeholder="正規表現" spellcheck="false" autocomplete="off">
                                    </div>
                                    <input type="hidden" class="fb-regex-value" value="">
                                </div>
                            </div>
                            <input type="hidden" data-name="checkbox_limit_enabled" class="fb-checkbox-limit-enabled" value="0">
                            <div class="fb-checkbox-limit-settings" style="display:none">
                                <div><label>最小選択数</label><input type="number" min="1" data-name="checkbox_min_selections"></div>
                                <div><label>最大選択数</label><input type="number" min="1" data-name="checkbox_max_selections"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fb-max-length-wrap"><label>最大文字数（任意）</label><input type="number" min="1" data-name="max_length"></div>
            </div>
            <div class="box is-field fb-options" style="display:none">
                <label>選択肢（1行に1つ）</label>
                <div class="fb-choiceEditor" data-choice-editor>
                    <div class="fb-choiceList"></div>
                    <div class="area is-actions">
                        <button type="button" class="btn is-sm fb-choice-add">＋ 選択肢を追加</button>
                    </div>
                    <textarea data-name="options" class="fb-options-source" rows="4" hidden></textarea>
                </div>
            </div>
        </div>
        <div class="fb-field-section">
            <h3 class="fb-field-section-title">表示設定</h3>
            <div class="fb-row">
                <div class="fb-placeholder-wrap"><label>プレースホルダ（任意）</label><input type="text" data-name="placeholder"></div>
            </div>
            <div class="fb-row fb-text-pair-settings" style="display:none">
                <div><label>1つ目の入力欄の表示名</label><input type="text" data-name="pair_label_left"></div>
                <div><label>2つ目の入力欄の表示名</label><input type="text" data-name="pair_label_right"></div>
            </div>
            <div class="fb-row fb-text-pair-placeholders" style="display:none">
                <div><label>1つ目のプレースホルダ（任意）</label><input type="text" data-name="pair_placeholder_left"></div>
                <div><label>2つ目のプレースホルダ（任意）</label><input type="text" data-name="pair_placeholder_right"></div>
            </div>
            <div class="box is-field"><label class="label">備考</label><textarea data-name="help_text" rows="3"></textarea></div>
        </div>
        <input type="hidden" data-name="_remove" value="0" class="fb-remove-flag">
        </div>
    </li>
</template>
