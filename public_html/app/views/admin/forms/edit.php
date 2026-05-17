<?php
require_once APP_COMPONENTS_DIR . '/c-formBlock01/c-formBlock01.php';
require_once APP_COMPONENTS_DIR . '/c-breadcrumb01/c-breadcrumb01.php';
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
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('説明文')); ?>
        </div>
        <div class="area is-field">
            <div data-module="c-descriptionEditor01" data-description-editor data-name-prefix="<?= e($namePrefix) ?>"
                data-position-mode="<?= e($positionMode) ?>">
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
                                        <?php admin_c_form_editor_item01_open([
                                            'data-description-block' => true,
                                            'data-index' => (string) $i,
                                        ], true, empty($block['visible'])); ?>
                                            <input type="hidden" name="<?= e($namePrefix) ?>[<?= $i ?>][id]"
                                                value="<?= e($blockId) ?>" data-name="id">
                                            <div class="wrapper">
                                                <div class="block is-header">
                                                    <?php admin_c_form_editor_item_header01_open(); ?>
                                                                <div class="area is-move">
                                                                    <div class="box is-handle ui-sortable-handle"
                                                                        title="ドラッグして並べ替え">
                                                                        <?php admin_m_drag_handle01('c-descriptionEditor__dragHandle'); ?>
                                                                    </div>
                                                                </div>
                                                                <div class="area is-summary">
                                                                    <div class="c-descriptionEditorSummary01">
                                                                        <div class="wrapper">
                                                                            <div class="block is-contents">
                                                                                <p class="label"
                                                                                    data-target="c-descriptionEditor__summaryLabel"
                                                                                    <?= $labelValue === '' ? ' hidden' : '' ?>>
                                                                                    <?= $labelValue !== '' ? e($labelValue) : '' ?>
                                                                                </p>
                                                                                <p class="position"
                                                                                    data-target="c-descriptionEditor__summaryPosition">
                                                                                    <?= e(((int) ($block['position'] ?? 0)) === 0 ? '先頭' : '末尾') ?>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="area is-delete">
                                                                    <label class="c-descriptionEditorSummary01__visibility"
                                                                        title="<?= !empty($block['visible']) ? '表示中' : '非表示' ?>">
                                                                        <input type="checkbox"
                                                                            name="<?= e($namePrefix) ?>[<?= $i ?>][visible]"
                                                                            value="1" data-name="visible"
                                                                            <?= !empty($block['visible']) ? 'checked' : '' ?>>
                                                                        <span aria-hidden="true">
                                                                            <svg width="16" height="16" viewBox="0 0 24 24"
                                                                                fill="none" stroke="currentColor"
                                                                                stroke-width="2">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                                                                                <circle cx="12" cy="12" r="3" />
                                                                                <path class="is-hiddenLine"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round" d="M4 20L20 4" />
                                                                            </svg>
                                                                        </span>
                                                                    </label>
                                                                    <?php admin_m_delete_btn01('fb-description-remove'); ?>
                                                                </div>
                                                    <?php admin_c_form_editor_item_header01_close(); ?>
                                                </div>
                                                <div class="block is-body">
                                                    <?php admin_c_form_editor_item_body01_open(); ?>
                                                            <div class="block is-contents">
                                                                <div class="block is-field">
                                                                    <?php admin_m_input_set01_open(); ?>
                                                                    <div class="area is-title">
                                                                        <?php admin_m_bold_title01(e('ラベル'), $labelInputId); ?>
                                                                    </div>
                                                                    <div class="area is-field">
                                                                        <?php admin_m_input01_open(); ?>
                                                                        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label($labelInputId, [
                                                                            'type' => 'text',
                                                                            'id' => $labelInputId,
                                                                            'name' => $namePrefix . '[' . $i . '][label]',
                                                                            'value' => (string) $labelValue,
                                                                            'data-name' => 'label',
                                                                            'data-target' => 'c-descriptionEditor__labelInput',
                                                                        ])); ?>
                                                                        <?php admin_m_input01_close(); ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    <?php admin_c_form_editor_item_body01_close(); ?>
                                                    <div class="block is-field">
                                                        <?php admin_m_input_set01_open(); ?>
                                                        <div class="area is-title">
                                                            <?php admin_m_bold_title01(e('表示位置'), $positionInputId); ?>
                                                        </div>
                                                        <div class="area is-field">
                                                            <?php admin_m_input01_open(); ?>
                                                            <?php admin_m_input01_select_open(admin_m_input01_attrs_with_bound_label($positionInputId, [
                                                                'id' => $positionInputId,
                                                                'name' => $namePrefix . '[' . $i . '][position]',
                                                                'class' => 'fb-description-position',
                                                                'data-name' => 'position',
                                                                'data-value' => (string) ($block['position'] ?? 0),
                                                            ])); ?>
                                                            <?php admin_m_input01_select_close(); ?>
                                                            <?php admin_m_input01_close(); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php admin_c_form_editor_item01_close(); ?>
                                        <div class="block is-field">
                                            <?php admin_m_input_set01_open(); ?>
                                            <div class="area is-title">
                                                <?php admin_m_bold_title01(e('説明文'), $htmlInputId); ?>
                                            </div>
                                            <div class="area is-field">
                                                <div data-module="c-htmlInputEditor01" data-html-toggle-editor>
                                                    <div class="wrapper">
                                                        <div class="block is-editor">
                                                            <?php admin_m_input01_open(); ?>
                                                            <?php admin_m_input01_textarea(admin_m_input01_attrs_with_bound_label($htmlInputId, [
                                                                'id' => $htmlInputId,
                                                                'name' => $namePrefix . '[' . $i . '][html]',
                                                                'rows' => 6,
                                                                'data-name' => 'html',
                                                                'data-target' => 'c-htmlInputEditor01__textarea',
                                                            ]), (string) ($block['html'] ?? '')); ?>
                                                            <?php admin_m_input01_close(); ?>
                                                        </div>
                                                        <div class="block is-toolbar">
                                                            <label class="label is-choice c-htmlInputEditor01__toggle">
                                                                <input type="checkbox"
                                                                    name="<?= e($namePrefix) ?>[<?= $i ?>][is_html]" value="1"
                                                                    data-name="is_html"
                                                                    data-target="c-htmlInputEditor01__toggle"
                                                                    <?= !empty($block['is_html']) ? 'checked' : '' ?>>
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
            <?php admin_m_btn01_open(); ?>
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

<?php admin_c_breadcrumb01([
    ['label' => 'フォーム', 'href' => base_path('/admin/forms')],
    ['label' => $breadcrumbTitle, 'current' => true],
]); ?>

<form method="post"
    action="<?= e($isNew ? base_path('/admin/forms') : base_path('/admin/forms/' . (int) $form['id'])) ?>"
    class="p-formEdit01" id="form-edit-main">
    <?= \Core\Csrf::field() ?>
    <?php admin_p_form_edit01_floating_actions($isNew); ?>
    </div>
    </div>
    <div class="wrapper">
        <div class="block is-contents">
            <?php if (!empty($errors)): ?>
                <div class="m-alert01 is-danger">
                    <ul><?php foreach ($errors as $msg): ?>
                            <li><?= e($msg) ?></li><?php endforeach; ?>
                    </ul>
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
                                <?php admin_m_btn01_open(); ?>
                                <button type="submit" class="btn is-primary"><?= $isNew ? '作成する' : '保存する' ?></button>
                            </div>
                            <?php admin_m_btn01_open(); ?>
                            <a class="btn" href="<?= e(base_path('/admin/forms')) ?>">キャンセル</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</form>

<?php admin_c_field_type_modal01(); ?>

<?php admin_m_form_builder01_description_template(); ?>

<?php admin_m_form_builder01_field_template(); ?>
