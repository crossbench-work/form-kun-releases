<?php
namespace Controllers\Admin;

use Core\Auth;
use Core\View;

class SettingsController
{
    public function index(): void
    {
        Auth::requireLogin();

        (new View())->render('admin/settings/index', [
            'title' => '設定',
        ], 'admin');
    }
}
