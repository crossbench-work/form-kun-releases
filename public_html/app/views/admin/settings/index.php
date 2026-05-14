<h1 class="title">設定</h1>

<div data-module="c-formBlock01">
    <div class="wrapper">
        <div class="block is-contents">
            <div class="block is-title">
                <h2 data-module="m-largeTitle01">サイト</h2>
            </div>
            <div class="block is-field">
                <dl class="admin-settings-dl">
                    <dt>管理画面の表示名</dt>
                    <dd><code><?= e((string) config('site.admin_name', '')) ?></code></dd>
                    <dt>公開サイトのベース URL</dt>
                    <dd><?php $baseUrl = (string) config('site.base_url', ''); if ($baseUrl !== ''): ?><code><?= e($baseUrl) ?></code><?php else: ?><span class="text is-muted">未設定（相対パスのみ）</span><?php endif; ?></dd>
                    <dt>サブディレクトリ（base_path）</dt>
                    <dd><code><?= e((string) config('site.base_path', '')) ?></code>（空ならドキュメントルート直下）</dd>
                    <dt>タイムゾーン</dt>
                    <dd><code><?= e((string) config('site.timezone', '')) ?></code></dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<div data-module="c-formBlock01">
    <div class="wrapper">
        <div class="block is-contents">
            <div class="block is-title">
                <h2 data-module="m-largeTitle01">フォーム</h2>
            </div>
            <div class="block is-field">
                <dl class="admin-settings-dl">
                    <dt>現在のフォーム数</dt>
                    <dd>
                        <span class="admin-settings-dl__countRow">
                            <span class="admin-settings-dl__countText"><?= (int) $form_count ?> 件 / 上限 <?= (int) $max_forms ?> 件</span>
                            <?php if (\Core\Auth::role() === 'admin'): ?>
                                <button type="button" class="btn is-sm" data-target="settings-max-forms-open">編集</button>
                            <?php endif; ?>
                        </span>
                    </dd>
                </dl>
                <?php if (\Core\Auth::role() !== 'admin'): ?>
                    <p class="text is-muted">作成上限の変更は管理者のみ行えます。</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (\Core\Auth::role() === 'admin'): ?>
<div data-module="c-settingsFormLimitModal01" hidden>
    <div class="c-settingsFormLimitModal01__backdrop" data-target="settings-max-forms-close"></div>
    <div class="c-settingsFormLimitModal01__dialog" role="dialog" aria-modal="true" aria-labelledby="settings-max-forms-modal-title">
        <div class="c-settingsFormLimitModal01__header">
            <h2 id="settings-max-forms-modal-title">フォームの作成上限</h2>
            <button type="button" class="btn is-sm" data-target="settings-max-forms-close">閉じる</button>
        </div>
        <div class="c-settingsFormLimitModal01__body">
            <form method="post" action="<?= e(base_path('/admin/settings')) ?>">
                <?= \Core\Csrf::field() ?>
                <div class="area is-field">
                    <label class="label" data-module="m-boldTitle01" for="max_forms_modal">フォームの作成上限</label>
                    <div data-module="m-input01">
                        <input type="number" name="max_forms" id="max_forms_modal" min="1" max="999999" step="1" value="<?= (int) $max_forms ?>" required>
                    </div>
                    <p class="text is-help">1 以上の整数を指定してください。保存後、一覧の新規作成やこの上限を超える作成はできません。</p>
                </div>
                <div class="c-settingsFormLimitModal01__actions">
                    <button type="button" class="btn" data-target="settings-max-forms-close">キャンセル</button>
                    <button type="submit" class="btn is-primary">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
