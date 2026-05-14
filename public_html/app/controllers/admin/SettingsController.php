<?php
namespace Controllers\Admin;

use Core\Auth;
use Core\Csrf;
use Core\View;
use Models\AppSetting;
use Models\Form;

class SettingsController
{
    public function index(): void
    {
        Auth::requireLogin();

        (new View())->render('admin/settings/index', [
            'title'      => '設定',
            'form_count' => Form::count(),
            'max_forms'  => effective_max_forms(),
        ], 'admin');
    }

    public function update(): void
    {
        Auth::requireRole('admin');
        Csrf::verify();

        $raw = trim((string) ($_POST['max_forms'] ?? ''));
        $v = filter_var($raw, FILTER_VALIDATE_INT);
        if ($v === false || $v < 1 || $v > 999999) {
            flash('danger', 'フォームの作成上限は 1 から 999999 までの整数で入力してください。');
            redirect('/admin/settings');
        }

        AppSetting::set(AppSetting::KEY_MAX_FORMS, (string) $v);
        flash('success', 'フォームの作成上限を保存しました。');
        redirect('/admin/settings');
    }
}
