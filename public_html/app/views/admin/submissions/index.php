<div class="m-toolbar01">
    <h1 class="title" style="margin:0;">送信履歴: <?= e($form['title']) ?></h1>
    <div class="spacer"></div>
    <a class="btn" href="<?= e(base_path('/admin/forms/' . $form['id'] . '/edit')) ?>">フォーム編集へ</a>
    <a class="btn" href="<?= e(base_path('/admin/forms')) ?>">フォーム一覧へ</a>
</div>

<div data-module="c-panel01">
    <div class="wrapper">
        <div class="block is-contents">
                <div data-module="c-formBlock01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="area is-field">
                                <form method="get" action="<?= e(base_path('/admin/forms/' . $form['id'] . '/submissions')) ?>" class="m-toolbar01">
                                    <input data-module="m-inout01" type="text" name="q" value="<?= e($q) ?>" placeholder="キーワード検索（送信内容）" style="width:280px">
                                    <label class="label">期間 <input data-module="m-inout01" type="date" name="from" value="<?= e($from) ?>"></label>
                                    <label class="label">〜 <input data-module="m-inout01" type="date" name="to" value="<?= e($to) ?>"></label>
                                    <button class="btn" type="submit">検索</button>
                                    <?php if ($q !== '' || $from !== '' || $to !== ''): ?>
                                        <a class="btn" href="<?= e(base_path('/admin/forms/' . $form['id'] . '/submissions')) ?>">リセット</a>
                                    <?php endif; ?>
                                    <span class="spacer"></span>
                                    <span class="text is-muted"><?= number_format($total) ?>件</span>
                                </form>

                                <?php if (empty($rows)): ?>
                                    <p class="text is-muted">該当する送信がありません。</p>
                                <?php else: ?>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>受信日時</th>
                                                <?php foreach ($previewKeys as $key):
                                                    $label = '';
                                                    foreach ($fields as $f) { if ($f['field_key'] === $key) { $label = $f['label']; break; } }
                                                ?>
                                                    <th><?= e($label ?: $key) ?></th>
                                                <?php endforeach; ?>
                                                <th>IP</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($rows as $r): ?>
                                            <tr>
                                                <td><?= e($r['submitted_at']) ?></td>
                                                <?php foreach ($previewKeys as $key):
                                                    $v = $previews[(int)$r['id']][$key] ?? '';
                                                    $fieldDef = null;
                                                    foreach ($fields as $f) {
                                                        if ($f['field_key'] === $key) {
                                                            $fieldDef = $f;
                                                            break;
                                                        }
                                                    }
                                                    if (($fieldDef['type'] ?? '') === 'address') {
                                                        $v = format_address_value_display($v);
                                                    } elseif (($fieldDef['type'] ?? '') === 'text_pair') {
                                                        $v = format_text_pair_value_display($v, \Models\FormField::decodeTextPairLabels($fieldDef['options_json'] ?? null));
                                                    }
                                                    $v = mb_substr((string) $v, 0, 40);
                                                ?>
                                                    <td><?= e($v) ?></td>
                                                <?php endforeach; ?>
                                                <td><?= e($r['ip']) ?></td>
                                                <td>
                                                    <a href="<?= e(base_path('/admin/submissions/' . $r['id'])) ?>">詳細</a> ｜
                                                    <form class="is-inline" method="post"
                                                          action="<?= e(base_path('/admin/submissions/' . $r['id'] . '/delete')) ?>"
                                                          onsubmit="return confirm('この送信を削除しますか？');">
                                                        <?= \Core\Csrf::field() ?>
                                                        <button type="submit" class="btn is-sm is-danger">削除</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                    <?php if ($totalPages > 1): ?>
                                    <div class="pagination">
                                        <?php
                                            $qs = function (int $p) use ($q, $from, $to) {
                                                $params = ['page' => $p];
                                                if ($q !== '')    $params['q'] = $q;
                                                if ($from !== '') $params['from'] = $from;
                                                if ($to !== '')   $params['to'] = $to;
                                                return '?' . http_build_query($params);
                                            };
                                            $base = base_path('/admin/forms/' . $form['id'] . '/submissions');
                                        ?>
                                        <?php if ($page > 1): ?>
                                            <a href="<?= e($base . $qs($page - 1)) ?>">‹ 前へ</a>
                                        <?php else: ?>
                                            <span class="disabled">‹ 前へ</span>
                                        <?php endif; ?>

                                        <?php for ($p = max(1, $page - 3); $p <= min($totalPages, $page + 3); $p++): ?>
                                            <?php if ($p === $page): ?>
                                                <span class="current"><?= $p ?></span>
                                            <?php else: ?>
                                                <a href="<?= e($base . $qs($p)) ?>"><?= $p ?></a>
                                            <?php endif; ?>
                                        <?php endfor; ?>

                                        <?php if ($page < $totalPages): ?>
                                            <a href="<?= e($base . $qs($page + 1)) ?>">次へ ›</a>
                                        <?php else: ?>
                                            <span class="disabled">次へ ›</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
