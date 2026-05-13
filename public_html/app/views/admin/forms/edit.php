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
    <div class="block is-field">
        <div data-module="c-descriptionEditor01" data-description-editor data-name-prefix="<?= e($namePrefix) ?>" data-position-mode="<?= e($positionMode) ?>">
            <div class="wrapper">
                <div class="block is-contents">
                    <div class="c-descriptionEditor01__editor">
                        <div class="wrapper" data-description-list>
                                <?php foreach ($blocks as $i => $block): ?>
                                    <div class="block is-contents" data-description-block data-index="<?= $i ?>">
                                        <input type="hidden" name="<?= e($namePrefix) ?>[<?= $i ?>][id]" value="<?= e($block['id'] ?? ($namePrefix . '-' . $i)) ?>" data-name="id">
                                        <div class="area is-header">
                                            <p data-module="m-boldTitle01">説明文</p>
                                            <button type="button" data-module="m-deleteBtn01" data-target="fb-description-remove" aria-label="削除" title="削除">
                                                <svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="area is-controls fb-row" style="display: flex;">
                                            <div class="fb-description-position-wrap">
                                                <div data-module="m-input01">
                                                    <select name="<?= e($namePrefix) ?>[<?= $i ?>][position]" class="fb-description-position" data-name="position" data-value="<?= e((string) ($block['position'] ?? 0)) ?>" aria-label="説明文"></select>
                                                </div>
                                            </div>
                                            <div class="fb-description-visible-wrap">
                                                <label class="label is-choice fb-basic-display-toggle">
                                                    <input type="checkbox" name="<?= e($namePrefix) ?>[<?= $i ?>][visible]" value="1" data-name="visible" <?= !empty($block['visible']) ? 'checked' : '' ?>>
                                                    表示する
                                                </label>
                                            </div>
                                        </div>
                                        <div class="area is-editor">
                                            <div data-module="m-input01">
                                                <textarea name="<?= e($namePrefix) ?>[<?= $i ?>][html]" rows="6" data-name="html"><?= e((string) ($block['html'] ?? '')) ?></textarea>
                                            </div>
                                        </div>
                                        <div class="area is-help">
                                            <p class="text is-help">HTML を直接入力できます。一部のタグと属性だけが保存されます。</p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="block is-toolbar">
                    <div class="area is-actions">
                        <button type="button" class="btn is-sm fb-description-add">説明文を追加する</button>
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
    <div data-module="c-panel01">
        <div class="wrapper">
            <div class="block is-contents">
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
                                <label class="label is-choice fb-basic-display-toggle">
                                    <input type="checkbox" name="show_title" value="1" <?= !empty($form['show_title']) ? 'checked' : '' ?>>
                                    表示する
                                </label>
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
            </div>
        </div>
    </div>
        </div>

        <div data-target="c-formEditTabs01__contents" data-tab-panel="fields" class="area is-contents" role="tabpanel" aria-label="入力画面" hidden>
    <div data-module="c-panel01">
        <div class="wrapper">
            <div class="block is-contents">
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
            <?php $renderDescriptionEditor('description_blocks', $descriptionBlocks, 'input'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-module="c-panel01">
        <div class="wrapper">
            <div class="block is-contents">
                <div data-module="c-formBlock01">
                    <div class="wrapper">
                        <div class="block is-contents" data-module="m-formBuilder01">
            <div class="block is-title">
                <h2 data-module="m-largeTitle01">項目設定</h2>
            </div>
            <div class="block is-field">
            <div class="c-formEditor">
                <div class="wrapper">
                    <div class="block is-contents" data-target="m-formBuilder01__fields">
            <?php foreach ($sortableFields as $i => $f): ?>
                <?php $idx = $i; $isAgreementField = false; ?>
                <?php include __DIR__ . '/_field_editor.php'; ?>
            <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="area is-actions">
                <button type="button" data-target="m-formBuilder01__add" class="btn">＋ 項目を追加</button>
            </div>
            <?php if ($agreementField !== null): ?>
                <div class="c-formEditor is-static">
                    <div class="wrapper">
                        <div class="block is-contents">
                    <?php $idx = count($sortableFields); $f = $agreementField; $isAgreementField = true; ?>
                    <?php include __DIR__ . '/_field_editor.php'; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-module="c-panel01">
        <div class="wrapper">
            <div class="block is-contents">
                <div data-module="c-formBlock01">
                    <div class="wrapper">
                        <div class="block is-contents">
            <div class="block is-title">
                <h2 data-module="m-largeTitle01">スパム対策</h2>
            </div>
            <div class="block is-field">
                <label class="label is-choice">
                    <input type="checkbox" name="captcha_enabled" value="1" <?= !empty($form['captcha_enabled']) ? 'checked' : '' ?>>
                    スパム対策（簡易計算CAPTCHA）を有効にする
                </label>
            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-module="c-panel01">
        <div class="wrapper">
            <div class="block is-contents">
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
        </div>
    </div>
        </div>

        <div data-target="c-formEditTabs01__contents" data-tab-panel="confirm" class="area is-contents" role="tabpanel" aria-label="確認画面" hidden>
    <div data-module="c-panel01">
        <div class="wrapper">
            <div class="block is-contents">
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
            <?php $renderDescriptionEditor('confirm_blocks', $confirmBlocks, 'simple'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-module="c-panel01">
        <div class="wrapper">
            <div class="block is-contents">
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
        </div>
    </div>
        </div>

        <div data-target="c-formEditTabs01__contents" data-tab-panel="thanks" class="area is-contents" role="tabpanel" aria-label="完了画面" hidden>
    <div data-module="c-panel01">
        <div class="wrapper">
            <div class="block is-contents">
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
            <?php $renderDescriptionEditor('thanks_blocks', $thanksBlocks, 'simple'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-module="c-panel01">
        <div class="wrapper">
            <div class="block is-contents">
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
        </div>
    </div>
        </div>

        <div data-target="c-formEditTabs01__contents" data-tab-panel="mail" class="area is-contents" role="tabpanel" aria-label="通知" hidden>
    <div data-module="c-panel01">
        <div class="wrapper">
            <div class="block is-contents">
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
                            <div class="area is-help">
                                <p class="text is-help">使用可能な変数: <code>{{field_key}}</code> / <code>{{__all__}}</code> / <code>{{__form_title__}}</code> / <code>{{__submitted_at__}}</code> / <code>{{__ip__}}</code></p>
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

    <div data-module="c-panel01">
        <div class="wrapper">
            <div class="block is-contents">
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
                                    <input type="text" name="reply_from_field_key" value="<?= e($form['reply_from_field_key']) ?>" placeholder="メール項目のフィールドキー（UUID または任意のキー）">
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

<template data-target="m-formBuilder01__descriptionTemplate">
    <div class="block is-contents" data-description-block>
        <input type="hidden" data-name="id" value="">
        <div class="area is-header">
            <p data-module="m-boldTitle01">説明文</p>
            <button type="button" data-module="m-deleteBtn01" data-target="fb-description-remove" aria-label="削除" title="削除">
                <svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/>
                </svg>
            </button>
        </div>
        <div class="area is-controls fb-row" style="display: flex;">
            <div class="fb-description-position-wrap">
                <div data-module="m-input01">
                    <select class="fb-description-position" data-name="position" data-value="0" aria-label="説明文"></select>
                </div>
            </div>
            <div class="fb-description-visible-wrap">
                <label class="label is-choice fb-basic-display-toggle">
                    <input type="checkbox" data-name="visible" value="1" checked>
                    表示する
                </label>
            </div>
        </div>
        <div class="area is-editor">
            <div data-module="m-input01">
                <textarea rows="6" data-name="html"></textarea>
            </div>
        </div>
        <div class="area is-help">
            <p class="text is-help">HTML を直接入力できます。一部のタグと属性だけが保存されます。</p>
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
                                    <div class="box is-button">
                                        <button type="button" data-module="m-arrowBtn01" data-type="up" data-target="c-formEditor__moveUp" aria-label="上へ移動" title="上へ移動">↑</button>
                                    </div>
                                    <div class="box is-button">
                                        <button type="button" data-module="m-arrowBtn01" data-type="down" data-target="c-formEditor__moveDown" aria-label="下へ移動" title="下へ移動">↓</button>
                                    </div>
                                </div>
                                <div class="area is-summary">
                                    <div class="c-formEditorSummary01">
                                        <div class="wrapper">
                                            <div class="block is-contents">
                                                <p class="index" data-target="c-formEditor__summaryIndex">1</p>
                                                <p class="label" data-target="c-formEditor__summaryLabel"></p>
                                                <p class="type" data-target="c-formEditor__summaryType"><?= e(\Models\FormField::typeLabel('text')) ?></p>
                                                <p class="required" data-target="c-formEditor__summaryRequired">任意</p>
                                                <p class="regex">入力チェック: <span data-target="c-formEditor__summaryRegex">なし</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="area is-delete">
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
                                                        <div class="block is-contents">
                                                            <div class="block is-title"><label class="label" data-module="m-labelTitle01">型</label></div>
                                                            <div class="block is-field"><div data-module="m-input01"><select data-name="type" data-target="c-formEditor__type"><?php foreach (\Models\FormField::TYPE_LABELS() as $tVal => $tLabel): ?><option value="<?= e($tVal) ?>"><?= e($tLabel) ?></option><?php endforeach; ?></select></div></div>
                                                        </div>
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
                                                            <div class="block is-title"><label class="label" data-module="m-labelTitle01">必須</label></div>
                                                            <div class="block is-field"><div class="c-formEditor__validationControls"><div class="c-formEditor__required"><label class="label is-choice"><input type="checkbox" data-name="required" value="1" data-target="c-formEditor__required"><span>必須にする</span></label></div><div data-target="c-formEditor__regex" style="display:none"><div class="c-formEditor__regexList"><div class="c-formEditor__regexRow"><div data-module="m-input01"><select data-target="c-formEditor__regexKind" aria-label="入力チェックの種類"><?php foreach ($regexPresetChoices as $patVal => $patLabel): ?><option value="<?= e($patVal) ?>"><?= e($patLabel) ?></option><?php endforeach; ?><option value="<?= e($FB_REGEX_CHECKBOX_LIMIT) ?>">選択数を設定する</option></select></div><div class="c-formEditor__regexCustom" style="display:none"><div data-module="m-input01"><input type="text" data-target="c-formEditor__regexCustomInput" placeholder="正規表現" spellcheck="false" autocomplete="off"></div></div><input type="hidden" data-target="c-formEditor__regexValue" value=""></div></div><input type="hidden" data-name="checkbox_limit_enabled" data-target="c-formEditor__checkboxLimitEnabled" value="0"><div class="c-formEditor__checkboxLimit" style="display:none"><div class="wrapper"><div class="block is-contents"><div class="block is-title"><label class="label" data-module="m-labelTitle01">最小選択数</label></div><div class="block is-field"><div data-module="m-input01"><input type="number" min="1" data-name="checkbox_min_selections"></div></div></div><div class="block is-contents"><div class="block is-title"><label class="label" data-module="m-labelTitle01">最大選択数</label></div><div class="block is-field"><div data-module="m-input01"><input type="number" min="1" data-name="checkbox_max_selections"></div></div></div></div></div></div>
                                                        </div>
                                                        <div class="block is-contents" data-target="c-formEditor__maxLength"><div class="block is-title"><label class="label" data-module="m-labelTitle01">最大文字数（任意）</label></div><div class="block is-field"><div data-module="m-input01"><input type="number" min="1" data-name="max_length"></div></div></div>
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
                                                <div class="c-formEditor__field" data-target="c-formEditor__textPairSettings" style="display:none">
                                                    <div class="wrapper">
                                                        <div class="block is-contents"><div class="block is-title"><label class="label" data-module="m-labelTitle01">1つ目の入力欄の表示名</label></div><div class="block is-field"><div data-module="m-input01"><input type="text" data-name="pair_label_left"></div></div></div>
                                                        <div class="block is-contents"><div class="block is-title"><label class="label" data-module="m-labelTitle01">2つ目の入力欄の表示名</label></div><div class="block is-field"><div data-module="m-input01"><input type="text" data-name="pair_label_right"></div></div></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="block is-field">
                                                <div class="c-formEditor__field" data-target="c-formEditor__textPairPlaceholders" style="display:none">
                                                    <div class="wrapper">
                                                        <div class="block is-contents"><div class="block is-title"><label class="label" data-module="m-labelTitle01">1つ目のプレースホルダ（任意）</label></div><div class="block is-field"><div data-module="m-input01"><input type="text" data-name="pair_placeholder_left"></div></div></div>
                                                        <div class="block is-contents"><div class="block is-title"><label class="label" data-module="m-labelTitle01">2つ目のプレースホルダ（任意）</label></div><div class="block is-field"><div data-module="m-input01"><input type="text" data-name="pair_placeholder_right"></div></div></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="block is-field">
                                                <label class="label" data-module="m-labelTitle01">備考</label>
                                                <div data-module="m-input01"><textarea data-name="help_text" rows="3"></textarea></div>
                                            </div>
                                <input type="hidden" data-name="_remove" value="0" data-target="c-formEditor__removeFlag">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
