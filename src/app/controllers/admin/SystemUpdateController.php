<?php
namespace Controllers\Admin;

use Core\AppUpdater;
use Core\Auth;
use Core\Csrf;
use Core\View;

class SystemUpdateController
{
    public function index(): void
    {
        Auth::requireRole('admin');

        $manifest = null;
        $manifestError = '';
        if (AppUpdater::isEnabled()) {
            $result = AppUpdater::fetchManifest();
            if ($result['ok']) {
                $manifest = $result['manifest'];
            } else {
                $manifestError = $result['message'];
            }
        }

        (new View())->render('admin/settings/update', [
            'title'          => 'アプリ更新',
            'currentVersion' => AppUpdater::currentVersion(),
            'requirements'   => AppUpdater::requirements(),
            'manifest'       => $manifest,
            'manifestError'  => $manifestError,
        ], 'admin');
    }

    public function run(): void
    {
        Auth::requireRole('admin');
        Csrf::verify();

        if (!AppUpdater::isEnabled()) {
            flash('danger', '更新機能が無効です。');
            redirect('/admin/settings/update');
        }

        $version = trim((string) ($_POST['version'] ?? ''));
        if ($version === '') {
            flash('danger', 'バージョンを指定してください。');
            redirect('/admin/settings/update');
        }

        $result = AppUpdater::applyRelease($version);
        flash($result['ok'] ? 'success' : 'danger', $result['message']);
        redirect('/admin/settings/update');
    }
}
