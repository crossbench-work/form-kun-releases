<h1 class="title">ダッシュボード</h1>

<section class="m-panel01">
    <div class="wrapper">
        <h2>フォーム別 受信件数</h2>
        <?php if (empty($byForm)): ?>
            <p class="text is-muted">まだフォームがありません。 <a href="<?= e(base_path('/admin/forms/new')) ?>">新規作成</a></p>
        <?php else: ?>
            <table class="table">
                <thead><tr><th>タイトル</th><th>状態</th><th class="num">受信</th><th>操作</th></tr></thead>
                <tbody>
                <?php foreach ($byForm as $r): ?>
                    <tr>
                        <td><?= e($r['title']) ?> <span class="text is-muted">(<?= e($r['slug']) ?>)</span></td>
                        <td><span class="m-badge01 is-<?= e($r['status']) ?>"><?= e($r['status']) ?></span></td>
                        <td class="num"><?= (int) $r['cnt'] ?></td>
                        <td>
                            <a href="<?= e(base_path('/admin/forms/' . $r['id'] . '/submissions')) ?>">送信履歴</a>
                            ｜
                            <a href="<?= e(base_path('/admin/forms/' . $r['id'] . '/edit')) ?>">編集</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<section class="m-panel01">
    <div class="wrapper">
        <h2>最近の送信</h2>
        <?php if (empty($recent)): ?>
            <p class="text is-muted">まだ送信はありません。</p>
        <?php else: ?>
            <table class="table">
                <thead><tr><th>受信日時</th><th>フォーム</th><th>IP</th><th>操作</th></tr></thead>
                <tbody>
                <?php foreach ($recent as $r): ?>
                    <tr>
                        <td><?= e($r['submitted_at']) ?></td>
                        <td><?= e($r['form_title']) ?></td>
                        <td><?= e($r['ip']) ?></td>
                        <td><a href="<?= e(base_path('/admin/submissions/' . $r['id'])) ?>">詳細</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>
