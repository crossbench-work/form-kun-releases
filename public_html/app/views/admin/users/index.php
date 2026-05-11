<div class="toolbar">
    <h1 style="margin:0;">ユーザー管理</h1>
    <div class="spacer"></div>
    <a class="btn btn-primary" href="<?= e(base_path('/admin/users/new')) ?>">＋ 新規作成</a>
</div>

<section class="panel">
<?php if (empty($users)): ?>
    <p class="muted">ユーザーがありません。</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>ユーザー名</th>
                <th>表示名</th>
                <th>メール</th>
                <th>権限</th>
                <th>最終ログイン</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><code><?= e($u['username']) ?></code></td>
                <td><?= e($u['display_name'] ?? '') ?></td>
                <td><?= e($u['email'] ?? '') !== '' ? e($u['email']) : '—' ?></td>
                <td><span class="badge badge-<?= e($u['role']) ?>"><?= e($u['role']) ?></span></td>
                <td><?= e($u['last_login_at'] ?? '-') ?></td>
                <td>
                    <a href="<?= e(base_path('/admin/users/' . $u['id'] . '/edit')) ?>">編集</a>
                    <?php if ((int) $u['id'] !== (int) \Core\Auth::id()): ?>
                        ｜
                        <form class="inline-form" method="post"
                              action="<?= e(base_path('/admin/users/' . $u['id'] . '/delete')) ?>"
                              onsubmit="return confirm('このユーザーを削除しますか？');">
                            <?= \Core\Csrf::field() ?>
                            <button type="submit" class="btn btn-sm btn-danger">削除</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</section>
