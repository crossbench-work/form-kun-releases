<?php
require_once APP_COMPONENTS_DIR . '/c-formBlock01/c-formBlock01.php';
require_once APP_COMPONENTS_DIR . '/c-inputSet01/c-inputSet01.php';
require_once APP_COMPONENTS_DIR . '/m-largeTitle01/m-largeTitle01.php';
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
        <?php admin_c_input_set01_open(); ?>
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
                                                                            <?php admin_c_input_set01_open(); ?>
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
                                                                            <?php admin_c_input_set01_open(); ?>
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
                                                                            <?php admin_c_input_set01_open(); ?>
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

<form method="post" action="<?= e($isNew ? base_path('/admin/forms') : base_path('/admin/forms/' . (int)$form['id'])) ?>" class="p-formEdit01" id="form-edit-main">
    <?= \Core\Csrf::field() ?>
    <div class="p-formEdit01__floatingActions" data-target="p-formEdit01__dirtyActions" hidden>
        <div class="wrapper">
            <div class="block is-status">
                <p class="text">未保存の変更があります</p>
            </div>
            <div class="block is-actions">
                <div data-module="m-btn01">
                    <button type="submit" class="btn is-primary"><?= $isNew ? '作成する' : '保存する' ?></button>
                </div>
                <div data-module="m-btn01">
                    <button type="button" class="btn" data-target="p-formEdit01__dirtyCancel">キャンセル</button>
                </div>
            </div>
        </div>
    </div>
    <div class="wrapper">
        <div class="block is-contents">
            <?php if (!empty($errors)): ?>
                <div class="m-alert01 is-danger">
                    <ul><?php foreach ($errors as $msg): ?><li><?= e($msg) ?></li><?php endforeach; ?></ul>
                </div>
            <?php endif; ?>

            <?php require __DIR__ . '/_form_edit_tabs.php'; ?>
            <?php require __DIR__ . '/_form_edit_tab_contents_basic.php'; ?>
            <?php require __DIR__ . '/_form_edit_tab_contents_fields.php'; ?>
            <?php require __DIR__ . '/_form_edit_tab_contents_confirm.php'; ?>
            <?php require __DIR__ . '/_form_edit_tab_contents_thanks.php'; ?>
            <?php require __DIR__ . '/_form_edit_tab_contents_mail.php'; ?>

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
                                <?php admin_c_input_set01_open(); ?>
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
                                <?php admin_c_input_set01_open(); ?>
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
                                <?php admin_c_input_set01_open(); ?>
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
                                                            <div class="area is-title"><label class="label" data-module="m-labelTitle01">ラベル</label></div>
                                                            <div class="area is-field"><div data-module="m-input01"><input type="text" data-name="label" data-target="c-formEditor__labelInput"></div></div>
                                                        </div>
                                                        <div class="block is-contents">
                                                            <div class="area is-title"><div class="c-formEditor__fieldKeyColumn"><label class="label" data-module="m-labelTitle01">フィールドキー</label></div></div>
                                                            <div class="area is-field"><div class="c-formEditor__fieldKey"><div data-module="m-input01"><input type="text" data-name="field_key" data-target="c-formEditor__fieldKeyInput" class="is-locked" readonly spellcheck="false"></div><button type="button" class="btn is-icon" data-target="c-formEditor__fieldKeyLock" aria-label="キーを編集" title="編集する" aria-pressed="true"><svg data-target="c-formEditor__fieldKeyLockedIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg><svg data-target="c-formEditor__fieldKeyOpenIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg></button></div></div>
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
                                                            <div class="area is-title">
                                                                <label class="label" data-module="m-labelTitle01">入力チェック</label>
                                                                <div data-module="m-requiredCheck01">
                                                                    <label class="label is-choice">
                                                                        <input type="checkbox" data-name="required" value="1" data-target="c-formEditor__required" aria-label="必須にする">
                                                                        <span>必須にする</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="area is-field">
                                                                <div class="c-formEditor__validationControls">
                                                                    <div data-target="c-formEditor__regex">
                                                                        <div class="c-formEditor__regexList">
                                                                            <div class="c-formEditor__regexRow">
                                                                                <div data-module="m-input01">
                                                                                    <select data-target="c-formEditor__regexKind" aria-label="入力チェックの種類"><?php foreach ($regexPresetChoices as $patVal => $patLabel): ?><option value="<?= e($patVal) ?>"><?= e($patLabel) ?></option><?php endforeach; ?><option value="<?= e($FB_REGEX_CHECKBOX_LIMIT) ?>">選択数を設定する</option></select>
                                                                                </div>
                                                                                <div class="c-formEditor__regexCustom" style="display:none">
                                                                                    <div data-module="m-input01">
                                                                                        <input type="text" data-target="c-formEditor__regexCustomInput" placeholder="正規表現" spellcheck="false" autocomplete="off">
                                                                                    </div>
                                                                                </div>
                                                                                <input type="hidden" data-target="c-formEditor__regexValue" value="">
                                                                            </div>
                                                                        </div>
                                                                        <input type="hidden" data-name="checkbox_limit_enabled" data-target="c-formEditor__checkboxLimitEnabled" value="0">
                                                                        <div class="c-formEditor__checkboxLimit" style="display:none">
                                                                            <div class="wrapper">
                                                                                <div class="block is-contents">
                                                                                    <div class="area is-title"><label class="label" data-module="m-labelTitle01">最小選択数</label></div>
                                                                                    <div class="area is-field"><div data-module="m-input01"><input type="number" min="1" data-name="checkbox_min_selections"></div></div>
                                                                                </div>
                                                                                <div class="block is-contents">
                                                                                    <div class="area is-title"><label class="label" data-module="m-labelTitle01">最大選択数</label></div>
                                                                                    <div class="area is-field"><div data-module="m-input01"><input type="number" min="1" data-name="checkbox_max_selections"></div></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="block is-contents" data-target="c-formEditor__minMaxLength"><div class="area is-title"><label class="label" data-module="m-labelTitle01">文字数制限（任意）</label></div><div class="area is-field"><div class="c-formEditor__child__field"><div class="wrapper"><div class="block is-contents"><div class="area is-title"><label data-module="m-labelSmallTitle01">最小</label></div><div class="area is-field"><div data-module="m-input01"><input type="number" min="1" data-name="min_length" placeholder="最小"></div></div></div><div class="block is-contents"><div class="area is-title"><label data-module="m-labelSmallTitle01">最大</label></div><div class="area is-field"><div data-module="m-input01"><input type="number" min="1" data-name="max_length" placeholder="最大"></div></div></div></div></div></div></div>
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
                                                                            <div class="block is-contents" data-target="c-formEditor__choiceListContents">
                                                                                <div class="area is-contents">
                                                                                    <div class="c-formEditor__choiceItem" data-choice-item>
                                                                                        <div class="wrapper">
                                                                                            <div class="block is-contents">
                                                                                                <div class="area is-move">
                                                                                                    <div class="box is-handle ui-sortable-handle" title="ドラッグして並べ替え">
                                                                                                        <p data-module="m-dragHandle01" data-target="c-formEditor__choiceDragHandle">
                                                                                                            <svg width="14" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="currentColor"><circle cx="9" cy="6" r="2"/><circle cx="15" cy="6" r="2"/><circle cx="9" cy="12" r="2"/><circle cx="15" cy="12" r="2"/><circle cx="9" cy="18" r="2"/><circle cx="15" cy="18" r="2"/></svg>
                                                                                                        </p>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="c-formEditor__child__field">
                                                                                                    <div class="wrapper">
                                                                                                        <div class="block is-contents">
                                                                                                            <div class="area is-field">
                                                                                                                <div data-module="m-input01"><input type="text" data-target="c-formEditor__choiceInput" placeholder="選択肢"></div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <button type="button" data-module="m-deleteBtn01" data-target="c-formEditor__choiceRemove" aria-label="削除" title="削除">
                                                                                                    <svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2"/><path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6"/><path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/></svg>
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="area is-contents" data-target="c-formEditor__choiceControls">
                                                                                    <div data-module="m-btn01" data-position="center">
                                                                                        <button type="button" class="btn is-add" data-target="c-formEditor__choiceAdd">＋ 選択肢を追加</button>
                                                                                        <textarea data-name="options" data-target="c-formEditor__optionsSource" rows="4" hidden></textarea>
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
                                                        <div class="block is-contents" data-target="c-formEditor__placeholder"><div class="area is-title"><label class="label" data-module="m-labelTitle01">プレースホルダ（任意）</label></div><div class="area is-field"><div data-module="m-input01"><input type="text" data-name="placeholder"></div></div></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="block is-field">
                                                <div class="c-formEditor__field" data-target="c-formEditor__textMultiSettings" style="display:none">
                                                    <div class="c-formEditor__textMultiList" data-text-multi-editor>
                                                        <div class="wrapper">
                                                            <div class="block is-contents" data-target="c-formEditor__textMultiListContents">
                                                                <div class="area is-contents">
                                                                    <div class="c-formEditor__textMultiItem" data-text-multi-item>
                                                                        <div class="wrapper">
                                                                            <div class="block is-contents">
                                                                                <div class="c-formEditor__child__field">
                                                                                    <div class="wrapper">
                                                                                        <div class="block is-contents"><div class="area is-title"><label data-module="m-labelSmallTitle01">表示</label></div><div class="area is-field"><div data-module="m-input01"><input type="text" data-target="c-formEditor__textMultiLabelInput"></div></div></div>
                                                                                        <div class="block is-contents"><div class="area is-title"><label data-module="m-labelSmallTitle01">入力例</label></div><div class="area is-field"><div data-module="m-input01"><input type="text" data-target="c-formEditor__textMultiPlaceholderInput"></div></div></div>
                                                                                    </div>
                                                                                </div>
                                                                                <button type="button" data-module="m-deleteBtn01" data-target="c-formEditor__textMultiRemove" aria-label="削除" title="削除"><svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2"/><path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6"/><path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/></svg></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="area is-contents" data-target="c-formEditor__textMultiControls">
                                                                    <div data-module="m-btn01" data-position="center">
                                                                        <button type="button" data-target="c-formEditor__textMultiAdd" class="btn is-add fb-description-add">＋ 入力エリアを追加</button>
                                                                        <textarea data-name="multi_text_items" data-target="c-formEditor__textMultiSource" hidden>[{"label":"","placeholder":""}]</textarea>
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
