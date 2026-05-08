<div class="toolbar">
    <h1 style="margin:0;">フォーム一覧</h1>
    <div class="spacer"></div>
    <a class="btn btn-primary" href="<?= e(base_path('/admin/forms/new')) ?>">＋ 新規作成</a>
</div>

<section class="panel">
<?php if (empty($forms)): ?>
    <p class="muted">まだフォームがありません。</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>タイトル</th>
                <th>スラッグ</th>
                <th>状態</th>
                <th>更新日時</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($forms as $f): ?>
            <tr>
                <td><strong><?= e($f['title']) ?></strong></td>
                <td><code><?= e($f['slug']) ?></code></td>
                <td><span class="badge badge-<?= e($f['status']) ?>"><?= e($f['status']) ?></span></td>
                <td><?= e($f['updated_at']) ?></td>
                <td>
                    <a href="<?= e(base_path('/admin/forms/' . $f['id'] . '/edit')) ?>">編集</a> ｜
                    <a href="<?= e(base_path('/admin/forms/' . $f['id'] . '/submissions')) ?>">送信履歴</a> ｜
                    <a href="<?= e(base_path('/f/' . $f['slug'])) ?>" target="_blank" rel="noopener">プレビュー</a> ｜
                    <form class="inline-form" method="post"
                          action="<?= e(base_path('/admin/forms/' . $f['id'] . '/delete')) ?>"
                          onsubmit="return confirm('本当に削除しますか？関連する送信履歴も削除されます。');">
                        <?= \Core\Csrf::field() ?>
                        <button type="submit" class="btn btn-sm btn-danger">削除</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</section>
