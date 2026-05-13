<?php
$fk = (string) ($f['field_key'] ?? '');
$fkLocked = $fk !== '' && \Core\FieldKey::isUuidFormat($fk);
$agreementDefaults = [
    'field_key' => 'agreement_consent',
    'type' => 'checkbox',
    'required' => 1,
    'checkbox_limit_enabled' => 0,
    'checkbox_min_selections' => '',
    'checkbox_max_selections' => '',
    'options' => '規約に同意する',
];
$regexDecoded = \Models\FormField::decodeRegexPatterns(isset($f['regex']) ? (string) $f['regex'] : null);
$rp = $regexDecoded[0] ?? '';
$isTextPair = (($f['type'] ?? '') === 'text_pair');
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
<li class="fb-field is-collapsed" data-index="<?= $idx ?>" data-field-item="1"<?= $isAgreementField ? ' data-is-agreement-field="1"' : '' ?>>
    <div class="fb-field-header">
        <?php if (!$isAgreementField): ?>
            <span class="fb-drag-handle" title="ドラッグして並べ替え">
                <svg width="14" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="currentColor"><circle cx="9" cy="6" r="2"/><circle cx="15" cy="6" r="2"/><circle cx="9" cy="12" r="2"/><circle cx="15" cy="12" r="2"/><circle cx="9" cy="18" r="2"/><circle cx="15" cy="18" r="2"/></svg>
            </span>
            <div class="fb-header-actions">
                <button type="button" class="btn is-sm fb-up" aria-label="上へ移動" title="上へ移動">↑</button>
                <button type="button" class="btn is-sm fb-down" aria-label="下へ移動" title="下へ移動">↓</button>
            </div>
        <?php endif; ?>
        <div class="fb-field-summary">
            <span class="fb-field-summary-main">
                <?php if ($isAgreementField): ?>
                    <strong class="fb-summary-label"><?= e($f['label']) ?></strong>
                <?php else: ?>
                    <span class="fb-num"><?= $idx + 1 ?></span>
                    · <strong class="fb-summary-label"><?= e($f['label']) ?></strong>
                    · <code class="fb-summary-type"><?= e(\Models\FormField::typeLabel($f['type'])) ?></code>
                    · <span class="fb-summary-badge fb-summary-required"><?= e($summaryRequired) ?></span>
                    · <span class="fb-summary-badge fb-summary-regex">入力チェック: <span class="fb-summary-regex-text"><?= e($summaryRegex) ?></span></span>
                <?php endif; ?>
            </span>
        </div>
        <?php if (!$isAgreementField): ?>
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
        <?php endif; ?>
    </div>
    <div class="fb-field-body">
        <div class="fb-field-section">
        <h3 class="fb-field-section-title">基本設定</h3>
        <div class="fb-row">
            <div>
                <label class="label" data-module="m-boldTitle01">ラベル</label>
                <div data-module="m-input01">
                    <input type="text" name="fields[<?= $idx ?>][label]" class="fb-input-label" value="<?= e($f['label']) ?>">
                </div>
            </div>
            <?php if ($isAgreementField): ?>
                <input type="hidden" name="fields[<?= $idx ?>][field_key]" value="<?= e($agreementDefaults['field_key']) ?>">
                <input type="hidden" name="fields[<?= $idx ?>][type]" value="<?= e($agreementDefaults['type']) ?>" class="fb-type">
            <?php else: ?>
                <div class="fb-field-key-col">
                    <label class="label" data-module="m-boldTitle01">フィールドキー</label>
                    <div class="fb-field-key-row">
                        <div data-module="m-input01">
                            <input type="text" name="fields[<?= $idx ?>][field_key]" value="<?= e($f['field_key']) ?>" class="fb-field-key-input<?= $fkLocked ? ' is-locked' : '' ?>" <?= $fkLocked ? 'readonly' : '' ?> spellcheck="false">
                        </div>
                        <button type="button" class="btn is-icon fb-field-key-lock" aria-label="<?= $fkLocked ? 'キーを編集' : 'キーをロック' ?>" title="<?= $fkLocked ? '編集する' : 'ロックする' ?>" aria-pressed="<?= $fkLocked ? 'true' : 'false' ?>">
                            <svg class="fb-icon-lock-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" <?= $fkLocked ? '' : 'style="display:none"' ?> aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <svg class="fb-icon-lock-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" <?= $fkLocked ? 'style="display:none"' : '' ?> aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="label" data-module="m-boldTitle01">型</label>
                    <div data-module="m-input01">
                        <select name="fields[<?= $idx ?>][type]" class="fb-type">
                        <?php foreach (\Models\FormField::TYPE_LABELS() as $tVal => $tLabel): ?>
                            <option value="<?= e($tVal) ?>" <?= $f['type'] === $tVal ? 'selected' : '' ?>><?= e($tLabel) ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($isAgreementField): ?>
        <input type="hidden" name="fields[<?= $idx ?>][required]" value="1" class="fb-required-toggle">
        <input type="hidden" name="fields[<?= $idx ?>][regex_pattern]" value="" class="fb-regex-value" spellcheck="false">
        <input type="hidden" name="fields[<?= $idx ?>][checkbox_limit_enabled]" class="fb-checkbox-limit-enabled" value="0">
        <input type="hidden" name="fields[<?= $idx ?>][checkbox_min_selections]" value="">
        <input type="hidden" name="fields[<?= $idx ?>][checkbox_max_selections]" value="">
        <textarea name="fields[<?= $idx ?>][options]" class="fb-options-source" hidden><?= e($agreementDefaults['options']) ?></textarea>
        <input type="hidden" name="fields[<?= $idx ?>][max_length]" value="">
    <?php else: ?>
        <div class="fb-field-section">
            <h3 class="fb-field-section-title">入力設定</h3>
            <div class="fb-row">
                <div class="fb-validation-wrap">
                    <label class="label" data-module="m-boldTitle01">必須</label>
                    <div class="fb-validation-controls">
                        <div class="fb-required-wrap">
                            <label class="label is-choice">
                                <input type="checkbox" name="fields[<?= $idx ?>][required]" value="1" class="fb-required-toggle" <?= !empty($f['required']) ? 'checked' : '' ?>>
                                <span>必須にする</span>
                            </label>
                        </div>
                        <div class="fb-regex-wrap" <?= (empty($f['required']) || in_array($f['type'], ['radio', 'select'], true)) ? 'style="display:none"' : '' ?>>
                            <div class="fb-regex-list">
                                <?php
                                if ($f['type'] === 'checkbox' && !empty($f['checkbox_limit_enabled'])) {
                                    $selVal = $FB_REGEX_CHECKBOX_LIMIT;
                                    $customVal = '';
                                } elseif ($f['type'] === 'checkbox') {
                                    $selVal = $FB_REGEX_CUSTOM;
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
                                <div class="fb-regex-row">
                                    <div data-module="m-input01">
                                        <select class="fb-regex-kind" aria-label="入力チェックの種類">
                                            <?php foreach ($regexPresetChoices as $patVal => $patLabel): ?>
                                                <?php if ($f['type'] === 'checkbox' && $patVal !== $FB_REGEX_CUSTOM): ?>
                                                    <?php continue; ?>
                                                <?php endif; ?>
                                                <option value="<?= e($patVal) ?>" <?= $selVal === $patVal ? 'selected' : '' ?>><?= e($patLabel) ?></option>
                                            <?php endforeach; ?>
                                            <?php if ($f['type'] === 'checkbox'): ?>
                                                <option value="<?= e($FB_REGEX_CHECKBOX_LIMIT) ?>" <?= $selVal === $FB_REGEX_CHECKBOX_LIMIT ? 'selected' : '' ?>>選択数を設定する</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="fb-regex-custom-wrap"<?= $showCustom ? '' : ' style="display:none"' ?>>
                                        <div data-module="m-input01">
                                            <input type="text" class="fb-regex-custom" value="<?= e($customVal) ?>" placeholder="正規表現" spellcheck="false" autocomplete="off">
                                        </div>
                                    </div>
                                    <input type="hidden" class="fb-regex-value" value="<?= e($hiddenVal) ?>" spellcheck="false">
                                </div>
                            </div>
                            <input type="hidden" name="fields[<?= $idx ?>][checkbox_limit_enabled]" class="fb-checkbox-limit-enabled" value="<?= !empty($f['checkbox_limit_enabled']) ? '1' : '0' ?>">
                            <div class="fb-checkbox-limit-settings" <?= ($f['type'] === 'checkbox' && !empty($f['required']) && !empty($f['checkbox_limit_enabled'])) ? '' : 'style="display:none"' ?>>
                                <div>
                    <label class="label" data-module="m-boldTitle01">最小選択数</label>
                                    <div data-module="m-input01">
                                        <input type="number" min="1" name="fields[<?= $idx ?>][checkbox_min_selections]" value="<?= e($f['checkbox_min_selections'] ?? '') ?>">
                                    </div>
                                </div>
                                <div>
                                    <label class="label" data-module="m-boldTitle01">最大選択数</label>
                                    <div data-module="m-input01">
                                        <input type="number" min="1" name="fields[<?= $idx ?>][checkbox_max_selections]" value="<?= e($f['checkbox_max_selections'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fb-max-length-wrap" <?= in_array($f['type'], ['radio', 'checkbox', 'select'], true) ? 'style="display:none"' : '' ?>>
                    <label class="label" data-module="m-boldTitle01">最大文字数（任意）</label>
                    <div data-module="m-input01">
                        <input type="number" min="1" name="fields[<?= $idx ?>][max_length]" value="<?= e($f['max_length']) ?>">
                    </div>
                </div>
            </div>
            <div class="area is-field fb-options" <?= in_array($f['type'], ['radio','checkbox','select'], true) ? '' : 'style="display:none"' ?>>
                <label class="label" data-module="m-boldTitle01">選択肢（1行に1つ）</label>
                <?php $choiceOptions = \Models\FormField::parseOptions($f['options'] ?? ''); ?>
                <div class="fb-choiceEditor" data-choice-editor>
                    <div class="fb-choiceList">
                        <?php foreach ($choiceOptions as $choiceOpt): ?>
                            <div class="fb-row fb-choiceItem" data-choice-item>
                                <div>
                                    <div data-module="m-input01">
                                        <input type="text" class="fb-choice-input" value="<?= e($choiceOpt) ?>" placeholder="選択肢">
                                    </div>
                                </div>
                                <div class="fb-actions">
                                    <button type="button" class="btn is-sm fb-choice-up">↑</button>
                                    <button type="button" class="btn is-sm fb-choice-down">↓</button>
                                    <button type="button" class="btn is-sm is-danger fb-choice-remove">削除</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="area is-actions">
                        <button type="button" class="btn is-sm fb-choice-add">＋ 選択肢を追加</button>
                    </div>
                    <textarea name="fields[<?= $idx ?>][options]" class="fb-options-source" rows="4" hidden><?= e($f['options'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="fb-field-section">
        <h3 class="fb-field-section-title">表示設定</h3>
        <div class="fb-row">
            <div class="fb-placeholder-wrap" <?= ($isTextPair || in_array($f['type'], ['radio', 'checkbox', 'select'], true)) ? 'style="display:none"' : '' ?>>
                <label class="label" data-module="m-boldTitle01">プレースホルダ（任意）</label>
                <div data-module="m-input01">
                    <input type="text" name="fields[<?= $idx ?>][placeholder]" value="<?= e($f['placeholder']) ?>">
                </div>
            </div>
        </div>
        <div class="fb-row fb-text-pair-settings" <?= $isTextPair ? '' : 'style="display:none"' ?>>
            <div>
                <label class="label" data-module="m-boldTitle01">1つ目の入力欄の表示名</label>
                <div data-module="m-input01">
                    <input type="text" name="fields[<?= $idx ?>][pair_label_left]" value="<?= e($f['pair_label_left'] ?? '') ?>">
                </div>
            </div>
            <div>
                <label class="label" data-module="m-boldTitle01">2つ目の入力欄の表示名</label>
                <div data-module="m-input01">
                    <input type="text" name="fields[<?= $idx ?>][pair_label_right]" value="<?= e($f['pair_label_right'] ?? '') ?>">
                </div>
            </div>
        </div>
        <div class="fb-row fb-text-pair-placeholders" <?= $isTextPair ? '' : 'style="display:none"' ?>>
            <div>
                <label class="label" data-module="m-boldTitle01">1つ目のプレースホルダ（任意）</label>
                <div data-module="m-input01">
                    <input type="text" name="fields[<?= $idx ?>][pair_placeholder_left]" value="<?= e($f['pair_placeholder_left'] ?? '') ?>">
                </div>
            </div>
            <div>
                <label class="label" data-module="m-boldTitle01">2つ目のプレースホルダ（任意）</label>
                <div data-module="m-input01">
                    <input type="text" name="fields[<?= $idx ?>][pair_placeholder_right]" value="<?= e($f['pair_placeholder_right'] ?? '') ?>">
                </div>
            </div>
        </div>
        <div class="area is-field">
            <?php if ($isAgreementField): ?>
                <label class="label" data-module="m-boldTitle01">説明文</label>
                <div data-module="m-input01">
                    <textarea name="fields[<?= $idx ?>][help_text]" rows="6"><?= e($f['help_text']) ?></textarea>
                </div>
                <p class="text is-help">HTML を直接入力できます。説明文はチェックボックスの上に表示されます。</p>
            <?php else: ?>
                <label class="label" data-module="m-boldTitle01">備考</label>
                <div data-module="m-input01">
                    <textarea name="fields[<?= $idx ?>][help_text]" rows="3"><?= e($f['help_text']) ?></textarea>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <input type="hidden" name="fields[<?= $idx ?>][_remove]" value="0" class="fb-remove-flag">
    </div>
</li>
