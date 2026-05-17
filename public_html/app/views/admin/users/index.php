<?php
require_once APP_COMPONENTS_DIR . '/m-table01/m-table01.php';
require_once APP_COMPONENTS_DIR . '/c-formBlock01/c-formBlock01.php';
?>
<div class="m-toolbar01">
    <h1 class="title" style="margin:0;">ユーザー管理</h1>
    <div class="spacer"></div>
    <?php admin_m_btn01_open(); ?>
        <?php admin_m_btn01_link('＋ 新規作成', base_path('/admin/users/new'), ['class' => 'is-primary']); ?>
    <?php admin_m_btn01_close(); ?>
</div>

<?php admin_c_form_block01_open(); ?>
                            <div class="area is-field">
                                <?php if (empty($users)): ?>
                                    <p class="text is-muted">ユーザーがありません。</p>
                                <?php else: ?>
                                    <?php admin_m_table01_open(); ?>
                                    <?php admin_m_table01_thead([
                                        ['label' => 'ユーザー名'],
                                        ['label' => '表示名'],
                                        ['label' => 'メール'],
                                        ['label' => '権限'],
                                        ['label' => '最終ログイン'],
                                        ['label' => '操作'],
                                    ]); ?>
                                        <tbody>
                                        <?php foreach ($users as $u): ?>
                                            <tr>
                                                <td><code><?= e($u['username']) ?></code></td>
                                                <td><?= e($u['display_name'] ?? '') ?></td>
                                                <td><?= e($u['email'] ?? '') !== '' ? e($u['email']) : '—' ?></td>
                                                <td><span class="m-badge01 is-<?= e($u['role']) ?>"><?= e($u['role']) ?></span></td>
                                                <td><?= e($u['last_login_at'] ?? '-') ?></td>
                                                <td>
                                                    <a href="<?= e(base_path('/admin/users/' . $u['id'] . '/edit')) ?>">編集</a>
                                                    <?php if ((int) $u['id'] !== (int) \Core\Auth::id()): ?>
                                                        ｜
                                                        <form class="is-inline" method="post"
                                                              action="<?= e(base_path('/admin/users/' . $u['id'] . '/delete')) ?>"
                                                              onsubmit="return confirm('このユーザーを削除しますか？');">
                                                            <?= \Core\Csrf::field() ?>
                                                            <button type="submit" class="btn is-sm is-danger">削除</button>
                                                        </form>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    <?php admin_m_table01_close(); ?>
                                <?php endif; ?>
                            </div>
<?php admin_c_form_block01_close(); ?>
