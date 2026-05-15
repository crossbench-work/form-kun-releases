<?php
$fk = (string) ($f['field_key'] ?? '');
$fkLocked = $fk !== '' && \Core\FieldKey::isUuidFormat($fk);
$regexDecoded = \Models\FormField::decodeRegexPatterns(isset($f['regex']) ? (string) $f['regex'] : null);
$rp = $regexDecoded[0] ?? '';
$isTextMulti = (($f['type'] ?? '') === 'text_multi');
$multiTextItems = $isTextMulti
    ? \Models\FormField::decodeTextMultiItems($f['multi_text_items'] ?? ($f['options_json'] ?? null), true)
    : [];
$helpTextIsHtml = preg_match('/<\s*\/?[a-zA-Z][^>]*>/', (string) ($f['help_text'] ?? '')) === 1;
$summaryRequired = !empty($f['required']) ? '必須' : '任意';
if (empty($f['required']) || in_array($f['type'], ['radio', 'select'], true)) {
    $summaryRegex = 'なし';
} elseif ($f['type'] === 'checkbox' && !empty($f['checkbox_limit_enabled'])) {
    $summaryRegex = '選択数';
} elseif ($f['type'] === 'checkbox') {
    $summaryRegex = array_key_exists($rp, $regexPresetChoices) ? 'なし' : 'カスタム';
} elseif ($rp === '') {
    $summaryRegex = 'なし';
} elseif (array_key_exists($rp, $regexPresetChoices)) {
    $summaryRegex = $regexPresetChoices[$rp];
} else {
    $summaryRegex = 'カスタム';
}
?>
<div class="c-formEditor__item is-collapsed<?= (array_key_exists('visible', $f) && empty($f['visible'])) ? ' is-editor-item-hidden' : '' ?>" data-index="<?= $idx ?>" data-field-item="1">
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
                                            <p class="index" data-target="c-formEditor__summaryIndex"><?= $idx + 1 ?></p>
                                            <p class="label" data-target="c-formEditor__summaryLabel"><?= e($f['label']) ?></p>
                                            <p class="type" data-target="c-formEditor__summaryType"><?= e(\Models\FormField::typeLabel($f['type'])) ?></p>
                                            <p class="required" data-target="c-formEditor__summaryRequired" <?= !empty($f['required']) ? '' : 'hidden' ?>><?= e($summaryRequired) ?></p>
                                            <p class="regex" data-target="c-formEditor__summaryRegexWrap" <?= (!empty($f['required']) && $summaryRegex !== 'なし') ? '' : 'hidden' ?>><svg width="12" height="12" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg><span data-target="c-formEditor__summaryRegex"><?= e($summaryRegex) ?></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="area is-delete">
                                <label class="c-formEditorSummary01__visibility" title="<?= !empty($f['visible']) ? '表示中' : '非表示' ?>">
                                    <input type="checkbox" name="fields[<?= $idx ?>][visible]" value="1" data-name="visible" <?= !array_key_exists('visible', $f) || !empty($f['visible']) ? 'checked' : '' ?>>
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
                                                        <div class="area is-title">
                                                            <label class="label" data-module="m-labelTitle01">ラベル</label>
                                                        </div>
                                                        <div class="area is-field">
                                                            <div data-module="m-input01">
                                                                <input type="text" name="fields[<?= $idx ?>][label]" data-target="c-formEditor__labelInput" value="<?= e($f['label']) ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="block is-contents">
                                                        <div class="area is-title">
                                                            <div class="c-formEditor__fieldKeyColumn">
                                                                <label class="label" data-module="m-labelTitle01">フィールドキー</label>
                                                            </div>
                                                        </div>
                                                        <div class="area is-field">
                                                            <div class="c-formEditor__fieldKey">
                                                                <div data-module="m-input01">
                                                                    <input type="text" name="fields[<?= $idx ?>][field_key]" value="<?= e($f['field_key']) ?>" data-target="c-formEditor__fieldKeyInput" class="<?= $fkLocked ? 'is-locked' : '' ?>" <?= $fkLocked ? 'readonly' : '' ?> spellcheck="false">
                                                                </div>
                                                                <button type="button" class="btn is-icon" data-target="c-formEditor__fieldKeyLock" aria-label="<?= $fkLocked ? 'キーを編集' : 'キーをロック' ?>" title="<?= $fkLocked ? '編集する' : 'ロックする' ?>" aria-pressed="<?= $fkLocked ? 'true' : 'false' ?>">
                                                                    <svg data-target="c-formEditor__fieldKeyLockedIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" <?= $fkLocked ? '' : 'style="display:none"' ?> aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                                    <svg data-target="c-formEditor__fieldKeyOpenIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" <?= $fkLocked ? 'style="display:none"' : '' ?> aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="fields[<?= $idx ?>][type]" value="<?= e($f['type']) ?>" data-target="c-formEditor__type" data-type-label="<?= e(\Models\FormField::typeLabel($f['type'])) ?>">
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
                                                                        <input type="checkbox" name="fields[<?= $idx ?>][required]" value="1" data-target="c-formEditor__required" <?= !empty($f['required']) ? 'checked' : '' ?> aria-label="必須にする">
                                                                        <span>必須にする</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="area is-field">
                                                                <div class="c-formEditor__validationControls">
                                                                    <div data-target="c-formEditor__regex">
                                                                        <div class="c-formEditor__regexList">
                                                                            <?php
                                                                            if ($f['type'] === 'checkbox' && !empty($f['checkbox_limit_enabled'])) {
                                                                                $selVal = $FB_REGEX_CHECKBOX_LIMIT;
                                                                                $customVal = '';
                                                                            } elseif ($f['type'] === 'checkbox') {
                                                                                $selVal = $rp !== '' ? $FB_REGEX_CUSTOM : '';
                                                                                $customVal = ($rp !== '' && !array_key_exists($rp, $regexPresetChoices)) ? $rp : '';
                                                                            } elseif ($rp === '') {
                                                                                $selVal = '';
                                                                                $customVal = '';
                                                                            } elseif ($rp !== '' && array_key_exists($rp, $regexPresetChoices) && $rp !== $FB_REGEX_CUSTOM) {
                                                                                $selVal = $rp;
                                                                                $customVal = '';
                                                                            } else {
                                                                                $selVal = $FB_REGEX_CUSTOM;
                                                                                $customVal = $rp;
                                                                            }
                                                                            $showCustom = ($selVal === $FB_REGEX_CUSTOM);
                                                                            $hiddenVal = $showCustom ? $customVal : ($selVal === $FB_REGEX_CHECKBOX_LIMIT ? '' : $selVal);
                                                                            ?>
                                                                            <div class="c-formEditor__regexRow">
                                                                                <div data-module="m-input01">
                                                                                    <select data-target="c-formEditor__regexKind" aria-label="入力チェックの種類" <?= empty($f['required']) ? 'disabled' : '' ?>>
                                                                                        <?php foreach ($regexPresetChoices as $patVal => $patLabel): ?>
                                                                                            <?php if ($f['type'] === 'checkbox' && !in_array($patVal, ['', $FB_REGEX_CUSTOM], true)): ?>
                                                                                                <?php continue; ?>
                                                                                            <?php endif; ?>
                                                                                            <?php if (in_array($f['type'], ['radio', 'select'], true) && $patVal !== ''): ?>
                                                                                                <?php continue; ?>
                                                                                            <?php endif; ?>
                                                                                            <option value="<?= e($patVal) ?>" <?= $selVal === $patVal ? 'selected' : '' ?>><?= e($patLabel) ?></option>
                                                                                        <?php endforeach; ?>
                                                                                        <?php if ($f['type'] === 'checkbox'): ?>
                                                                                            <option value="<?= e($FB_REGEX_CHECKBOX_LIMIT) ?>" <?= $selVal === $FB_REGEX_CHECKBOX_LIMIT ? 'selected' : '' ?>>選択数を設定する</option>
                                                                                        <?php endif; ?>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="c-formEditor__regexCustom"<?= $showCustom ? '' : ' style="display:none"' ?>>
                                                                                    <div data-module="m-input01">
                                                                                        <input type="text" data-target="c-formEditor__regexCustomInput" value="<?= e($customVal) ?>" placeholder="正規表現" spellcheck="false" autocomplete="off" <?= empty($f['required']) ? 'disabled' : '' ?>>
                                                                                    </div>
                                                                                </div>
                                                                                <input type="hidden" data-target="c-formEditor__regexValue" value="<?= e($hiddenVal) ?>" spellcheck="false">
                                                                            </div>
                                                                        </div>
                                                                        <input type="hidden" name="fields[<?= $idx ?>][checkbox_limit_enabled]" data-target="c-formEditor__checkboxLimitEnabled" value="<?= !empty($f['checkbox_limit_enabled']) ? '1' : '0' ?>">
                                                                        <div class="c-formEditor__checkboxLimit" <?= ($f['type'] === 'checkbox' && !empty($f['required']) && !empty($f['checkbox_limit_enabled'])) ? '' : 'style="display:none"' ?>>
                                                                            <div class="wrapper">
                                                                                <div class="block is-contents">
                                                                                    <div class="area is-title">
                                                                                        <label class="label" data-module="m-labelTitle01">最小選択数</label>
                                                                                    </div>
                                                                                    <div class="area is-field">
                                                                                        <div data-module="m-input01">
                                                                                            <input type="number" min="1" name="fields[<?= $idx ?>][checkbox_min_selections]" value="<?= e($f['checkbox_min_selections'] ?? '') ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="block is-contents">
                                                                                    <div class="area is-title">
                                                                                        <label class="label" data-module="m-labelTitle01">最大選択数</label>
                                                                                    </div>
                                                                                    <div class="area is-field">
                                                                                        <div data-module="m-input01">
                                                                                            <input type="number" min="1" name="fields[<?= $idx ?>][checkbox_max_selections]" value="<?= e($f['checkbox_max_selections'] ?? '') ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="block is-contents" data-target="c-formEditor__minMaxLength" <?= in_array($f['type'], ['radio', 'checkbox', 'select'], true) ? 'style="display:none"' : '' ?>>
                                                            <div class="area is-title">
                                                                <label class="label" data-module="m-labelTitle01">文字数制限（任意）</label>
                                                            </div>
                                                            <div class="area is-field">
                                                                <div style="display: flex; gap: 12px;">
                                                                    <div style="flex: 1;">
                                                                        <div data-module="m-input01">
                                                                            <input type="number" min="1" name="fields[<?= $idx ?>][min_length]" value="<?= e($f['min_length'] ?? '') ?>" placeholder="最小">
                                                                        </div>
                                                                    </div>
                                                                    <div style="flex: 1;">
                                                                        <div data-module="m-input01">
                                                                            <input type="number" min="1" name="fields[<?= $idx ?>][max_length]" value="<?= e($f['max_length']) ?>" placeholder="最大">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="block is-field" data-target="c-formEditor__options" <?= in_array($f['type'], ['radio','checkbox','select'], true) ? '' : 'style="display:none"' ?>>
                                                <label class="label" data-module="m-labelTitle01">選択肢（1行に1つ）</label>
                                                <?php $choiceOptions = \Models\FormField::parseOptions($f['options'] ?? ''); ?>
                                                <div class="c-formEditor__choiceEditor" data-choice-editor>
                                                    <div class="c-formEditor__choiceList">
                                                        <?php foreach ($choiceOptions as $choiceOpt): ?>
                                                            <div class="c-formEditor__field c-formEditor__choiceItem" data-choice-item>
                                                                <div>
                                                                    <div data-module="m-input01">
                                                                        <input type="text" data-target="c-formEditor__choiceInput" value="<?= e($choiceOpt) ?>" placeholder="選択肢">
                                                                    </div>
                                                                </div>
                                                                <div class="c-formEditor__actions">
                                                                    <button type="button" class="btn is-sm" data-target="c-formEditor__choiceUp">↑</button>
                                                                    <button type="button" class="btn is-sm" data-target="c-formEditor__choiceDown">↓</button>
                                                                    <button type="button" class="btn is-sm is-danger" data-target="c-formEditor__choiceRemove">削除</button>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class="area is-actions">
                                                        <button type="button" class="btn is-sm" data-target="c-formEditor__choiceAdd">＋ 選択肢を追加</button>
                                                    </div>
                                                    <textarea name="fields[<?= $idx ?>][options]" data-target="c-formEditor__optionsSource" rows="4" hidden><?= e($f['options'] ?? '') ?></textarea>
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
                                                    <div class="block is-contents" data-target="c-formEditor__placeholder" <?= ($isTextMulti || in_array($f['type'], ['radio', 'checkbox', 'select'], true)) ? 'style="display:none"' : '' ?>>
                                                        <div class="area is-title">
                                                            <label class="label" data-module="m-labelTitle01">プレースホルダ（任意）</label>
                                                        </div>
                                                        <div class="area is-field">
                                                            <div data-module="m-input01">
                                                                <input type="text" name="fields[<?= $idx ?>][placeholder]" value="<?= e($f['placeholder']) ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="block is-field">
                                            <div class="c-formEditor__field" data-target="c-formEditor__textMultiSettings" <?= $isTextMulti ? '' : 'style="display:none"' ?>>
                                                <div class="c-formEditor__textMultiList" data-text-multi-editor>
                                                    <div class="wrapper">
                                                        <div class="block is-contents" data-target="c-formEditor__textMultiListContents">
                                                            <?php foreach ($multiTextItems as $multiTextIndex => $multiTextItem): ?>
                                                                <div class="c-formEditor__textMultiItem" data-text-multi-item>
                                                                    <div class="wrapper">
                                                                        <div class="block is-contents">
                                                                            <div class="c-formEditor__child__field">
                                                                                <div class="wrapper">
                                                                                    <div class="block is-contents">
                                                                                        <div class="area is-title">
                                                                                            <label class="label" data-module="m-labelSmallTitle01">表示</label>
                                                                                        </div>
                                                                                        <div class="area is-field">
                                                                                            <div data-module="m-input01">
                                                                                                <input type="text" data-target="c-formEditor__textMultiLabelInput" value="<?= e($multiTextItem['label'] ?? '') ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="block is-contents">
                                                                                        <div class="area is-title">
                                                                                            <label class="label" data-module="m-labelSmallTitle01">入力例</label>
                                                                                        </div>
                                                                                        <div class="area is-field">
                                                                                            <div data-module="m-input01">
                                                                                                <input type="text" data-target="c-formEditor__textMultiPlaceholderInput" value="<?= e($multiTextItem['placeholder'] ?? '') ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <button type="button" data-module="m-deleteBtn01" data-target="c-formEditor__textMultiRemove" aria-label="削除" title="削除">
                                                                                <svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/>
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2"/>
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6"/>
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/>
                                                                                </svg>
                                                                            </button>
                                                                        </div>
                                                                        <?php if ($multiTextIndex === array_key_last($multiTextItems)): ?>
                                                                            <div class="block is-contents" data-target="c-formEditor__textMultiControls">
                                                                                <div data-module="m-btn01" data-position="center">
                                                                                    <button type="button" data-target="c-formEditor__textMultiAdd" class="btn is-add fb-description-add">＋ 入力エリアを追加</button>
                                                                                    <textarea name="fields[<?= $idx ?>][multi_text_items]" data-target="c-formEditor__textMultiSource" hidden><?= e(json_encode($multiTextItems, JSON_UNESCAPED_UNICODE)) ?></textarea>
                                                                                </div>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="block is-field">
                                            <label class="label" data-module="m-labelTitle01">備考</label>
                                            <div data-module="c-htmlInputEditor01" data-html-toggle-editor>
                                                <div class="wrapper">
                                                    <div class="block is-editor">
                                                        <div data-module="m-input01">
                                                            <textarea name="fields[<?= $idx ?>][help_text]" rows="3" data-target="c-htmlInputEditor01__textarea"><?= e($f['help_text']) ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="block is-toolbar">
                                                        <label class="label is-choice c-htmlInputEditor01__toggle">
                                                            <input type="checkbox" name="fields[<?= $idx ?>][help_text_is_html]" value="1" data-target="c-htmlInputEditor01__toggle" <?= $helpTextIsHtml ? 'checked' : '' ?>>
                                                            HTMLで入力する
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            <input type="hidden" name="fields[<?= $idx ?>][_remove]" value="0" data-target="c-formEditor__removeFlag">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
