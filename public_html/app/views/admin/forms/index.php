<?php
require_once APP_COMPONENTS_DIR . '/m-table01/m-table01.php';
require_once APP_COMPONENTS_DIR . '/c-formBlock01/c-formBlock01.php';
?>
<div class="m-toolbar01">
    <h1 class="title" style="margin:0;">フォーム一覧</h1>
    <div class="spacer"></div>
    <?php if (!empty($at_form_limit)): ?>
        <?php admin_m_btn01_open(); ?>
        <button type="button" class="btn is-primary" disabled aria-disabled="true">＋ 新規作成</button>
    </div>
<?php else: ?>
    <?php admin_m_btn01_open(); ?>
    <a class="btn is-primary" href="<?= e(base_path('/admin/forms/new')) ?>">＋ 新規作成</a>
    </div>
<?php endif; ?>
</div>

<?php admin_c_form_block01_open(); ?>
<div class="area is-field">
    <?php if (empty($forms)): ?>
        <p class="text is-muted">まだフォームがありません。<?php if (empty($at_form_limit)): ?><a
                    href="<?= e(base_path('/admin/forms/new')) ?>">新規作成</a><?php endif; ?></p>
    <?php else: ?>
        <?php admin_m_table01_open(); ?>
        <?php admin_m_table01_thead([
            ['label' => 'タイトル'],
            ['label' => 'スラッグ'],
            ['label' => '状態'],
            ['label' => '更新日時'],
            ['label' => '操作'],
        ]); ?>
        <tbody>
            <?php foreach ($forms as $f): ?>
                <tr>
                    <td><strong><?= e($f['title']) ?></strong></td>
                    <td><code><?= e($f['slug']) ?></code></td>
                    <td><span
                            class="m-badge01 is-<?= e($f['status']) ?>"><?= e(\Models\Form::statusLabel((string) $f['status'])) ?></span>
                    </td>
                    <td><?= e($f['updated_at']) ?></td>
                    <td>
                        <a href="<?= e(base_path('/admin/forms/' . $f['id'] . '/edit')) ?>">編集</a> ｜
                        <a href="<?= e(base_path('/admin/forms/' . $f['id'] . '/submissions')) ?>">送信履歴</a> ｜
                        <a href="<?= e(base_path('/f/' . $f['slug'])) ?>" target="_blank" rel="noopener">プレビュー</a> ｜
                        <form class="is-inline" method="post"
                            action="<?= e(base_path('/admin/forms/' . $f['id'] . '/delete')) ?>"
                            onsubmit="return confirm('本当に削除しますか？関連する送信履歴も削除されます。');">
                            <?= \Core\Csrf::field() ?>
                            <button type="submit" class="btn is-sm is-danger">削除</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <?php admin_m_table01_close(); ?>
    <?php endif; ?>
</div>
<?php admin_c_form_block01_close(); ?>