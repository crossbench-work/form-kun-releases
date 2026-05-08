<div class="toolbar">
    <h1 style="margin:0;">送信詳細 #<?= (int) $sub['id'] ?></h1>
    <div class="spacer"></div>
    <a class="btn" href="<?= e(base_path('/admin/forms/' . $form['id'] . '/submissions')) ?>">一覧へ戻る</a>
    <form class="inline-form" method="post"
          action="<?= e(base_path('/admin/submissions/' . $sub['id'] . '/delete')) ?>"
          onsubmit="return confirm('この送信を削除しますか？');">
        <?= \Core\Csrf::field() ?>
        <button type="submit" class="btn btn-danger">この送信を削除</button>
    </form>
</div>

<section class="panel">
    <table class="kv-table">
        <tr><th>フォーム</th><td><?= e($form['title']) ?>（<code><?= e($form['slug']) ?></code>）</td></tr>
        <tr><th>受信日時</th><td><?= e($sub['submitted_at']) ?></td></tr>
        <tr><th>IPアドレス</th><td><?= e($sub['ip']) ?></td></tr>
        <tr><th>User-Agent</th><td><?= e($sub['user_agent']) ?></td></tr>
    </table>
</section>

<section class="panel">
    <h2>入力内容</h2>
    <table class="kv-table">
    <?php foreach ($fields as $f):
        $key = $f['field_key'];
    ?>
        <tr>
            <th><?= e($f['label']) ?><br><span class="muted"><?= e($key) ?></span></th>
            <td>
            <?php if ($f['type'] === 'file'): ?>
                <?php $files = $filesByKey[$key] ?? []; ?>
                <?php if (empty($files)): ?>
                    <span class="muted">（ファイルなし）</span>
                <?php else: ?>
                    <?php foreach ($files as $fl): ?>
                        <div>
                            <a href="<?= e(base_path('/admin/files/' . $fl['id'])) ?>">
                                <?= e($fl['original_name']) ?>
                            </a>
                            <span class="muted">（<?= e(\Core\Validator::formatBytes((int) $fl['size'])) ?> / <?= e($fl['mime_type']) ?>）</span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php else:
                $val = $values[$key] ?? '';
            ?>
                <?= $val !== '' ? nl2br(e((string) $val)) : '<span class="muted">（未入力）</span>' ?>
            <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>

    <?php
        // フォーム項目に存在しないキー（過去に項目だった等）も表示
        $fieldKeys = array_map(fn($f) => $f['field_key'], $fields);
        foreach ($values as $k => $v):
            if (in_array($k, $fieldKeys, true)) continue;
    ?>
        <tr>
            <th>(削除済み項目)<br><span class="muted"><?= e($k) ?></span></th>
            <td><?= nl2br(e((string) $v)) ?></td>
        </tr>
    <?php endforeach; ?>
    </table>
</section>
