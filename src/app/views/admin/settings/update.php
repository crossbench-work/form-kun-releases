<?php

$latest = is_array($manifest ?? null) ? (string) ($manifest['latest'] ?? '') : '';
$releases = is_array($manifest ?? null) ? ($manifest['releases'] ?? []) : [];
?>
<h1 class="title">アプリ更新</h1>
<p><a href="<?= e(base_path('/admin/settings')) ?>">← 設定へ戻る</a></p>

<div class="block" style="margin:1rem 0;">
    <p>現在のバージョン: <code><?= e($currentVersion) ?></code></p>
    <?php if ($latest !== '' && $latest !== $currentVersion): ?>
        <p>最新リリース: <code><?= e($latest) ?></code></p>
    <?php endif; ?>
    <ul>
        <li>更新機能: <?= !empty($requirements['enabled']) ? '有効' : '無効（config.local.php の update.enabled）' ?></li>
        <li>manifest URL: <?= !empty($requirements['manifest_url']) ? '設定済み' : '未設定' ?></li>
        <li>ZipArchive: <?= !empty($requirements['zip']) ? '利用可' : '不可' ?></li>
    </ul>
</div>

<?php if ($manifestError !== ''): ?>
    <p class="text is-danger"><?= e($manifestError) ?></p>
<?php endif; ?>

<?php if (is_array($manifest) && $releases !== []): ?>
    <h2 class="title is-sm">利用可能なリリース（channel: <?= e((string) ($manifest['channel'] ?? '')) ?>）</h2>
    <table class="m-table01">
        <thead>
            <tr><th>版</th><th>日付</th><th>メモ</th><th></th></tr>
        </thead>
        <tbody>
            <?php foreach ($releases as $row): ?>
                <?php if (!is_array($row)) continue; ?>
                <tr>
                    <td><code><?= e((string) ($row['version'] ?? '')) ?></code></td>
                    <td><?= e((string) ($row['released_at'] ?? '')) ?></td>
                    <td><?= e((string) ($row['notes'] ?? '')) ?></td>
                    <td>
                        <?php if (!empty($requirements['enabled']) && !empty($requirements['zip']) && ($row['version'] ?? '') !== $currentVersion): ?>
                            <form method="post" action="<?= e(base_path('/admin/settings/update')) ?>" onsubmit="return confirm('バージョン <?= e((string) $row['version']) ?> に更新します。よろしいですか？');">
                                <?= \Core\Csrf::field() ?>
                                <input type="hidden" name="version" value="<?= e((string) $row['version']) ?>">
                                <button type="submit" class="btn is-primary is-sm">この版に更新</button>
                            </form>
                        <?php else: ?>
                            <span class="text is-muted">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php elseif (!empty($requirements['enabled']) && $manifestError === ''): ?>
    <p class="text is-muted">manifest を取得できませんでした。</p>
<?php endif; ?>
