<?php
require_once APP_COMPONENTS_DIR . '/m-table01/m-table01.php';
require_once APP_COMPONENTS_DIR . '/c-formBlock01/c-formBlock01.php';
?>
<div class="m-toolbar01">
    <h1 class="title" style="margin:0;">送信詳細 #<?= (int) $sub['id'] ?></h1>
    <div class="spacer"></div>
    <a class="btn" href="<?= e(base_path('/admin/forms/' . $form['id'] . '/submissions')) ?>">一覧へ戻る</a>
    <form class="is-inline" method="post"
          action="<?= e(base_path('/admin/submissions/' . $sub['id'] . '/delete')) ?>"
          onsubmit="return confirm('この送信を削除しますか？');">
        <?= \Core\Csrf::field() ?>
        <button type="submit" class="btn is-danger">この送信を削除</button>
    </form>
</div>

<?php admin_c_form_block01_open(); ?>
                            <div class="area is-field">
                                <?php admin_m_table01_open('is-kv'); ?>
                                    <tr><th>フォーム</th><td><?= e($form['title']) ?>（<code><?= e($form['slug']) ?></code>）</td></tr>
                                    <tr><th>受信日時</th><td><?= e($sub['submitted_at']) ?></td></tr>
                                    <tr><th>IPアドレス</th><td><?= e($sub['ip']) ?></td></tr>
                                    <tr><th>User-Agent</th><td><?= e($sub['user_agent']) ?></td></tr>
                                <?php admin_m_table01_close(); ?>
                            </div>
<?php admin_c_form_block01_close(); ?>

<?php admin_c_form_block01_open(); ?>
                            <div class="area is-title">
                                <h2 data-module="m-largeTitle01">入力内容</h2>
                            </div>
                            <div class="area is-field">
                                <?php admin_m_table01_open('is-kv'); ?>
                                <?php foreach ($fields as $f):
                                    $key = $f['field_key'];
                                ?>
                                    <tr>
                                        <th><?= e($f['label']) ?><br><span class="text is-muted"><?= e($key) ?></span></th>
                                        <td>
                                        <?php if ($f['type'] === 'file'): ?>
                                            <?php $files = $filesByKey[$key] ?? []; ?>
                                            <?php if (empty($files)): ?>
                                                <span class="text is-muted">（ファイルなし）</span>
                                            <?php else: ?>
                                                <?php foreach ($files as $fl): ?>
                                                    <div>
                                                        <a href="<?= e(base_path('/admin/files/' . $fl['id'])) ?>">
                                                            <?= e($fl['original_name']) ?>
                                                        </a>
                                                        <span class="text is-muted">（<?= e(\Core\Validator::formatBytes((int) $fl['size'])) ?> / <?= e($fl['mime_type']) ?>）</span>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        <?php else:
                                            $val = $values[$key] ?? '';
                                        ?>
                                            <?php if ($f['type'] === 'address'): ?>
                                                <?php $addrText = format_address_value_display($val); ?>
                                                <?= $addrText !== '' ? nl2br(e($addrText)) : '<span class="text is-muted">（未入力）</span>' ?>
                                            <?php elseif ($f['type'] === 'text_pair'): ?>
                                                <?php $pairText = format_text_pair_value_display($val, \Models\FormField::decodeTextPairLabels($f['options_json'] ?? null)); ?>
                                                <?= $pairText !== '' ? nl2br(e($pairText)) : '<span class="text is-muted">（未入力）</span>' ?>
                                            <?php elseif ($f['type'] === 'text_multi'): ?>
                                                <?php $multiText = format_text_multi_value_display($val, \Models\FormField::decodeTextMultiItems($f['options_json'] ?? null, true)); ?>
                                                <?= $multiText !== '' ? nl2br(e($multiText)) : '<span class="text is-muted">（未入力）</span>' ?>
                                            <?php else: ?>
                                                <?= $val !== '' ? nl2br(e((string) $val)) : '<span class="text is-muted">（未入力）</span>' ?>
                                            <?php endif; ?>
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
                                        <th>(削除済み項目)<br><span class="text is-muted"><?= e($k) ?></span></th>
                                        <td><?= nl2br(e((string) $v)) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php admin_m_table01_close(); ?>
                            </div>
<?php admin_c_form_block01_close(); ?>
