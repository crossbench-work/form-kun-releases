<?php
require_once APP_COMPONENTS_DIR . '/m-table01/m-table01.php';
require_once APP_COMPONENTS_DIR . '/c-formBlock01/c-formBlock01.php';
?>
<h1 class="title">ダッシュボード</h1>

<?php admin_c_form_block01_open(); ?>
                            <div class="area is-title">
                                <h2 data-module="m-largeTitle01">フォーム別 受信件数</h2>
                            </div>
                            <div class="area is-field">
                                <?php if (empty($byForm)): ?>
                                    <p class="text is-muted">まだフォームがありません。<?php if (empty($at_form_limit)): ?> <a href="<?= e(base_path('/admin/forms/new')) ?>">新規作成</a><?php endif; ?></p>
                                <?php else: ?>
                                    <?php admin_m_table01_open(); ?>
                                    <?php admin_m_table01_thead([
                                        ['label' => 'タイトル'],
                                        ['label' => '状態'],
                                        ['label' => '受信', 'class' => 'num'],
                                        ['label' => '操作'],
                                    ]); ?>
                                        <tbody>
                                        <?php foreach ($byForm as $r): ?>
                                            <tr>
                                                <td><?= e($r['title']) ?> <span class="text is-muted">(<?= e($r['slug']) ?>)</span></td>
                                                <td><span class="m-badge01 is-<?= e($r['status']) ?>"><?= e(\Models\Form::statusLabel((string) $r['status'])) ?></span></td>
                                                <td class="num"><?= (int) $r['cnt'] ?></td>
                                                <td>
                                                    <a href="<?= e(base_path('/admin/forms/' . $r['id'] . '/submissions')) ?>">送信履歴</a>
                                                    ｜
                                                    <a href="<?= e(base_path('/admin/forms/' . $r['id'] . '/edit')) ?>">編集</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    <?php admin_m_table01_close(); ?>
                                <?php endif; ?>
                            </div>
<?php admin_c_form_block01_close(); ?>

<?php admin_c_form_block01_open(); ?>
                            <div class="area is-title">
                                <h2 data-module="m-largeTitle01">最近の送信</h2>
                            </div>
                            <div class="area is-field">
                                <?php if (empty($recent)): ?>
                                    <p class="text is-muted">まだ送信はありません。</p>
                                <?php else: ?>
                                    <?php admin_m_table01_open(); ?>
                                    <?php admin_m_table01_thead([
                                        ['label' => '受信日時'],
                                        ['label' => 'フォーム'],
                                        ['label' => 'IP'],
                                        ['label' => '操作'],
                                    ]); ?>
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
                                    <?php admin_m_table01_close(); ?>
                                <?php endif; ?>
                            </div>
<?php admin_c_form_block01_close(); ?>
