<h1><?= $isNew ? 'フォーム新規作成' : 'フォーム編集' ?></h1>

<?php
$FB_REGEX_CUSTOM = '__custom__';
$regexPresetChoices = [
    '' => 'なし',
    '/\A[0-9]+\z/u' => '半角数字のみ',
    '/\A[a-zA-Z0-9]+\z/u' => '半角英数字のみ',
    '/\A[ぁ-んー]+\z/u' => 'ひらがなのみ',
    '/\A[ァ-ヶー　]+\z/u' => 'カタカナのみ（全角スペース可）',
    '/\A[a-zA-Z]+\z/u' => '半角英字のみ',
    $FB_REGEX_CUSTOM => 'カスタム（正規表現を入力）',
];
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul><?php foreach ($errors as $msg): ?><li><?= e($msg) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= e($isNew ? base_path('/admin/forms') : base_path('/admin/forms/' . (int)$form['id'])) ?>" class="form-edit-form">
    <?= \Core\Csrf::field() ?>

    <div class="form-edit-tabs">
        <div class="tablist" role="tablist" aria-label="フォーム設定のセクション">
            <button type="button" class="tablist-tab" role="tab" id="tab-btn-basic" aria-selected="true" aria-controls="tab-panel-basic">基本設定</button>
            <button type="button" class="tablist-tab" role="tab" id="tab-btn-fields" aria-selected="false" aria-controls="tab-panel-fields" tabindex="-1">項目設定</button>
            <button type="button" class="tablist-tab" role="tab" id="tab-btn-mail" aria-selected="false" aria-controls="tab-panel-mail" tabindex="-1">通知メール</button>
            <button type="button" class="tablist-tab" role="tab" id="tab-btn-thanks" aria-selected="false" aria-controls="tab-panel-thanks" tabindex="-1">送信完了画面</button>
        </div>

        <div id="tab-panel-basic" class="tab-panel" role="tabpanel" aria-labelledby="tab-btn-basic">
    <section class="panel">
        <h2>基本設定</h2>
        <div class="field">
            <label for="title">タイトル <span class="required">必須</span></label>
            <input type="text" id="title" name="title" value="<?= e($form['title']) ?>" required>
        </div>
        <div class="field">
            <label for="slug">スラッグ（URL用） <span class="required">必須</span></label>
            <input type="text" id="slug" name="slug" value="<?= e($form['slug']) ?>" required>
            <p class="field-help">公開URL: <code><?= e(url('/f/')) ?></code><strong id="slug-preview"><?= e($form['slug']) ?: 'your-slug' ?></strong></p>
        </div>
        <div class="field">
            <label for="status">公開状態</label>
            <select id="status" name="status">
                <option value="draft"    <?= $form['status'] === 'draft' ? 'selected' : '' ?>>下書き</option>
                <option value="active"   <?= $form['status'] === 'active' ? 'selected' : '' ?>>公開中</option>
                <option value="archived" <?= $form['status'] === 'archived' ? 'selected' : '' ?>>停止</option>
            </select>
        </div>
        <div class="field">
            <label for="description">説明文</label>
            <textarea id="description" name="description" rows="3"><?= e($form['description']) ?></textarea>
        </div>
        <div class="field">
            <label>
                <input type="checkbox" name="captcha_enabled" value="1" <?= !empty($form['captcha_enabled']) ? 'checked' : '' ?>>
                スパム対策（簡易計算CAPTCHA）を有効にする
            </label>
        </div>
    </section>
        </div>

        <div id="tab-panel-fields" class="tab-panel" role="tabpanel" aria-labelledby="tab-btn-fields" hidden>
    <section class="panel">
        <h2>項目設定（フォームビルダー）</h2>
        <ul id="fb-fields" class="fb-fields">
        <?php foreach ($fields as $i => $f): ?>
            <?php
            $idx = $i;
            $fk = (string) ($f['field_key'] ?? '');
            $fkLocked = $fk !== '' && \Core\FieldKey::isUuidFormat($fk);
            $regexDecoded = \Models\FormField::decodeRegexPatterns(isset($f['regex']) ? (string) $f['regex'] : null);
            $rp = $regexDecoded[0] ?? '';
            ?>
            <li class="fb-field is-collapsed" data-index="<?= $idx ?>">
                <div class="fb-field-header">
                    <span class="fb-drag-handle" title="ドラッグして並べ替え">
                        <svg width="14" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="currentColor"><circle cx="9" cy="6" r="2"/><circle cx="15" cy="6" r="2"/><circle cx="9" cy="12" r="2"/><circle cx="15" cy="12" r="2"/><circle cx="9" cy="18" r="2"/><circle cx="15" cy="18" r="2"/></svg>
                    </span>
                    <button type="button" class="fb-field-toggle" aria-expanded="false" title="詳細を開く／閉じる">
                        <span class="fb-field-toggle-icon" aria-hidden="true">▼</span>
                    </button>
                    <div class="fb-field-summary">
                        <span class="fb-field-summary-main">
                            項目 <span class="fb-num"><?= $idx + 1 ?></span>
                            · <strong class="fb-summary-label"><?= e($f['label']) ?></strong>
                            · <code class="fb-summary-type"><?= e(\Models\FormField::typeLabel($f['type'])) ?></code>
                        </span>
                    </div>
                </div>
                <div class="fb-field-body">
                <div class="fb-row">
                    <div>
                        <label>ラベル</label>
                        <input type="text" name="fields[<?= $idx ?>][label]" class="fb-input-label" value="<?= e($f['label']) ?>">
                    </div>
                    <div class="fb-field-key-col">
                        <label>フィールドキー</label>
                        <div class="fb-field-key-row">
                            <input type="text" name="fields[<?= $idx ?>][field_key]" value="<?= e($f['field_key']) ?>" class="fb-field-key-input<?= $fkLocked ? ' is-locked' : '' ?>" <?= $fkLocked ? 'readonly' : '' ?> spellcheck="false">
                            <button type="button" class="btn btn-icon fb-field-key-lock" aria-label="<?= $fkLocked ? 'キーを編集' : 'キーをロック' ?>" title="<?= $fkLocked ? '編集する' : 'ロックする' ?>" aria-pressed="<?= $fkLocked ? 'true' : 'false' ?>">
                                <svg class="fb-icon-lock-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" <?= $fkLocked ? '' : 'style="display:none"' ?> aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <svg class="fb-icon-lock-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" <?= $fkLocked ? 'style="display:none"' : '' ?> aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label>型</label>
                        <select name="fields[<?= $idx ?>][type]" class="fb-type">
                        <?php foreach (\Models\FormField::TYPE_LABELS() as $tVal => $tLabel): ?>
                            <option value="<?= e($tVal) ?>" <?= $f['type'] === $tVal ? 'selected' : '' ?>><?= e($tLabel) ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label>必須</label>
                        <label class="choice">
                            <input type="checkbox" name="fields[<?= $idx ?>][required]" value="1" <?= !empty($f['required']) ? 'checked' : '' ?>>
                            <span>必須にする</span>
                        </label>
                    </div>
                </div>
                <div class="fb-row">
                    <div>
                        <label>最大文字数（任意）</label>
                        <input type="number" min="1" name="fields[<?= $idx ?>][max_length]" value="<?= e($f['max_length']) ?>">
                    </div>
                    <div class="fb-regex-wrap">
                        <label>入力チェック</label>
                        <div class="fb-regex-list">
                            <?php
                            if ($rp === '') {
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
                            $hiddenVal = $showCustom ? $customVal : $selVal;
                            ?>
                            <div class="fb-regex-row">
                                <select class="fb-regex-kind" aria-label="入力チェックの種類">
                                    <?php foreach ($regexPresetChoices as $patVal => $patLabel): ?>
                                        <option value="<?= e($patVal) ?>" <?= $selVal === $patVal ? 'selected' : '' ?>><?= e($patLabel) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="fb-regex-custom-wrap"<?= $showCustom ? '' : ' style="display:none"' ?>>
                                    <input type="text" class="fb-regex-custom" value="<?= e($customVal) ?>" placeholder="正規表現" spellcheck="false" autocomplete="off">
                                </div>
                                <input type="hidden" class="fb-regex-value" value="<?= e($hiddenVal) ?>" spellcheck="false">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label>プレースホルダ（任意）</label>
                        <input type="text" name="fields[<?= $idx ?>][placeholder]" value="<?= e($f['placeholder']) ?>">
                    </div>
                </div>
                <div class="field">
                    <label>ヘルプテキスト（任意）</label>
                    <input type="text" name="fields[<?= $idx ?>][help_text]" value="<?= e($f['help_text']) ?>">
                </div>
                <div class="field fb-options" <?= in_array($f['type'], ['radio','checkbox','select'], true) ? '' : 'style="display:none"' ?>>
                    <label>選択肢（1行に1つ）</label>
                    <textarea name="fields[<?= $idx ?>][options]" rows="4"><?= e($f['options'] ?? '') ?></textarea>
                </div>
                <input type="hidden" name="fields[<?= $idx ?>][_remove]" value="0" class="fb-remove-flag">
                <div class="fb-actions">
                    <button type="button" class="btn btn-sm fb-up">↑ 上へ</button>
                    <button type="button" class="btn btn-sm fb-down">↓ 下へ</button>
                    <button type="button" class="btn btn-sm btn-danger fb-remove">削除</button>
                </div>
                </div>
            </li>
        <?php endforeach; ?>
        </ul>

        <div class="actions">
            <button type="button" id="fb-add" class="btn">＋ 項目を追加</button>
        </div>
    </section>
        </div>

        <div id="tab-panel-mail" class="tab-panel" role="tabpanel" aria-labelledby="tab-btn-mail" hidden>
    <section class="panel">
        <h2>管理者通知メール</h2>
        <div class="field">
            <label>送信先メールアドレス（カンマ区切りで複数可）</label>
            <input type="text" name="admin_emails" value="<?= e($form['admin_emails']) ?>" placeholder="admin@example.com, sub@example.com">
        </div>
        <div class="field">
            <label>件名</label>
            <input type="text" name="mail_subject_admin" value="<?= e($form['mail_subject_admin']) ?>" placeholder="[{{__form_title__}}] 新規送信">
        </div>
        <div class="field">
            <label>本文テンプレート</label>
            <textarea name="mail_template_admin" rows="6"><?= e($form['mail_template_admin']) ?></textarea>
            <p class="field-help">使用可能な変数: <code>{{field_key}}</code> / <code>{{__all__}}</code> / <code>{{__form_title__}}</code> / <code>{{__submitted_at__}}</code> / <code>{{__ip__}}</code></p>
        </div>
    </section>

    <section class="panel">
        <h2>送信者への自動返信メール</h2>
        <div class="field">
            <label>
                <input type="checkbox" name="reply_enabled" value="1" <?= !empty($form['reply_enabled']) ? 'checked' : '' ?>>
                自動返信を有効にする
            </label>
        </div>
        <div class="field">
            <label>送信先のフィールドキー（メール型項目）</label>
            <input type="text" name="reply_from_field_key" value="<?= e($form['reply_from_field_key']) ?>" placeholder="メール項目のフィールドキー（UUID または任意のキー）">
        </div>
        <div class="field">
            <label>件名</label>
            <input type="text" name="reply_subject" value="<?= e($form['reply_subject']) ?>">
        </div>
        <div class="field">
            <label>本文テンプレート</label>
            <textarea name="reply_template" rows="6"><?= e($form['reply_template']) ?></textarea>
        </div>
    </section>
        </div>

        <div id="tab-panel-thanks" class="tab-panel" role="tabpanel" aria-labelledby="tab-btn-thanks" hidden>
    <section class="panel">
        <h2>送信完了画面</h2>
        <div class="field">
            <label>サンクスメッセージ（リダイレクトURL未指定時に表示）</label>
            <textarea name="thanks_message" rows="3"><?= e($form['thanks_message']) ?></textarea>
        </div>
        <div class="field">
            <label>リダイレクトURL（任意）</label>
            <input type="text" name="redirect_url" value="<?= e($form['redirect_url']) ?>" placeholder="https://example.com/thanks">
        </div>
    </section>
        </div>

    </div>

    <div class="actions">
        <button type="submit" class="btn btn-primary"><?= $isNew ? '作成する' : '保存する' ?></button>
        <a class="btn" href="<?= e(base_path('/admin/forms')) ?>">キャンセル</a>
    </div>
</form>

<template id="fb-template">
    <li class="fb-field is-collapsed">
        <div class="fb-field-header">
            <span class="fb-drag-handle" title="ドラッグして並べ替え">
                <svg width="14" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="currentColor"><circle cx="9" cy="6" r="2"/><circle cx="15" cy="6" r="2"/><circle cx="9" cy="12" r="2"/><circle cx="15" cy="12" r="2"/><circle cx="9" cy="18" r="2"/><circle cx="15" cy="18" r="2"/></svg>
            </span>
            <button type="button" class="fb-field-toggle" aria-expanded="false" title="詳細を開く／閉じる">
                <span class="fb-field-toggle-icon" aria-hidden="true">▼</span>
            </button>
            <div class="fb-field-summary">
                <span class="fb-field-summary-main">
                    項目 <span class="fb-num">1</span>
                    · <strong class="fb-summary-label"></strong>
                    · <code class="fb-summary-type"><?= e(\Models\FormField::typeLabel('text')) ?></code>
                </span>
            </div>
        </div>
        <div class="fb-field-body">
        <div class="fb-row">
            <div><label>ラベル</label><input type="text" data-name="label" class="fb-input-label"></div>
            <div class="fb-field-key-col">
                <label>フィールドキー</label>
                <div class="fb-field-key-row">
                    <input type="text" data-name="field_key" class="fb-field-key-input is-locked" readonly spellcheck="false">
                    <button type="button" class="btn btn-icon fb-field-key-lock" aria-label="キーを編集" title="編集する" aria-pressed="true">
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
            <div>
                <label>必須</label>
                <label class="choice"><input type="checkbox" data-name="required" value="1"><span>必須にする</span></label>
            </div>
        </div>
        <div class="fb-row">
            <div><label>最大文字数（任意）</label><input type="number" min="1" data-name="max_length"></div>
            <div class="fb-regex-wrap">
                <label>入力チェック</label>
                <div class="fb-regex-list">
                    <div class="fb-regex-row">
                        <select class="fb-regex-kind" aria-label="入力チェックの種類">
                            <?php foreach ($regexPresetChoices as $patVal => $patLabel): ?>
                                <option value="<?= e($patVal) ?>"><?= e($patLabel) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="fb-regex-custom-wrap" style="display:none">
                            <input type="text" class="fb-regex-custom" placeholder="正規表現" spellcheck="false" autocomplete="off">
                        </div>
                        <input type="hidden" class="fb-regex-value" value="">
                    </div>
                </div>
            </div>
            <div><label>プレースホルダ（任意）</label><input type="text" data-name="placeholder"></div>
        </div>
        <div class="field"><label>ヘルプテキスト（任意）</label><input type="text" data-name="help_text"></div>
        <div class="field fb-options" style="display:none">
            <label>選択肢（1行に1つ）</label>
            <textarea data-name="options" rows="4"></textarea>
        </div>
        <input type="hidden" data-name="_remove" value="0" class="fb-remove-flag">
        <div class="fb-actions">
            <button type="button" class="btn btn-sm fb-up">↑ 上へ</button>
            <button type="button" class="btn btn-sm fb-down">↓ 下へ</button>
            <button type="button" class="btn btn-sm btn-danger fb-remove">削除</button>
        </div>
        </div>
    </li>
</template>
