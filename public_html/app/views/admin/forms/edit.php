<h1><?= $isNew ? 'フォーム新規作成' : 'フォーム編集' ?></h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul><?php foreach ($errors as $msg): ?><li><?= e($msg) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= e($isNew ? base_path('/admin/forms') : base_path('/admin/forms/' . (int)$form['id'])) ?>">
    <?= \Core\Csrf::field() ?>

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

    <section class="panel">
        <h2>項目設定（フォームビルダー）</h2>
        <p class="muted">必要な項目を追加し、ラベル・型・必須・選択肢などを設定してください。フィールドキーはメール本文や履歴で使われる識別子です（例: name, email）。</p>

        <ul id="fb-fields" class="fb-fields">
        <?php foreach ($fields as $i => $f): ?>
            <?php $idx = $i; ?>
            <li class="fb-field" data-index="<?= $idx ?>">
                <div class="fb-handle">項目 #<span class="fb-num"><?= $idx + 1 ?></span></div>
                <div class="fb-row">
                    <div>
                        <label>ラベル</label>
                        <input type="text" name="fields[<?= $idx ?>][label]" value="<?= e($f['label']) ?>">
                    </div>
                    <div>
                        <label>フィールドキー</label>
                        <input type="text" name="fields[<?= $idx ?>][field_key]" value="<?= e($f['field_key']) ?>">
                    </div>
                    <div>
                        <label>型</label>
                        <select name="fields[<?= $idx ?>][type]" class="fb-type">
                        <?php foreach (\Models\FormField::ALLOWED_TYPES() as $t): ?>
                            <option value="<?= e($t) ?>" <?= $f['type'] === $t ? 'selected' : '' ?>><?= e($t) ?></option>
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
                    <div>
                        <label>正規表現（任意）</label>
                        <input type="text" name="fields[<?= $idx ?>][regex]" value="<?= e($f['regex']) ?>" placeholder="例: /^[0-9]+$/">
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
            </li>
        <?php endforeach; ?>
        </ul>

        <div class="actions">
            <button type="button" id="fb-add" class="btn">＋ 項目を追加</button>
        </div>
    </section>

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
            <input type="text" name="reply_from_field_key" value="<?= e($form['reply_from_field_key']) ?>" placeholder="email">
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

    <div class="actions">
        <button type="submit" class="btn btn-primary"><?= $isNew ? '作成する' : '保存する' ?></button>
        <a class="btn" href="<?= e(base_path('/admin/forms')) ?>">キャンセル</a>
    </div>
</form>

<template id="fb-template">
    <li class="fb-field">
        <div class="fb-handle">項目 #<span class="fb-num"></span></div>
        <div class="fb-row">
            <div><label>ラベル</label><input type="text" data-name="label"></div>
            <div><label>フィールドキー</label><input type="text" data-name="field_key"></div>
            <div>
                <label>型</label>
                <select data-name="type" class="fb-type">
                    <?php foreach (\Models\FormField::ALLOWED_TYPES() as $t): ?>
                        <option value="<?= e($t) ?>"><?= e($t) ?></option>
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
            <div><label>正規表現（任意）</label><input type="text" data-name="regex"></div>
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
    </li>
</template>
