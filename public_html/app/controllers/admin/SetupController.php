<?php
namespace Controllers\Admin;

use Core\Csrf;
use Core\View;
use Models\User;

class SetupController
{
    public function show(): void
    {
        if (User::count() > 0) {
            flash('info', '初期セットアップは既に完了しています。');
            redirect('/admin/login');
        }
        (new View())->render('admin/auth/setup', [
            'title' => '初期セットアップ',
            'errors' => [],
            'old' => [],
        ], 'admin_minimal');
    }

    public function create(): void
    {
        if (User::count() > 0) {
            flash('info', '初期セットアップは既に完了しています。');
            redirect('/admin/login');
        }
        Csrf::verify();

        $username = trim((string) ($_POST['username'] ?? ''));
        $display  = trim((string) ($_POST['display_name'] ?? ''));
        $pass     = (string) ($_POST['password'] ?? '');
        $passConf = (string) ($_POST['password_confirm'] ?? '');

        $errors = [];
        if (!preg_match('/\A[a-zA-Z0-9_.\-]{3,64}\z/', $username)) {
            $errors[] = 'ユーザー名は半角英数字・記号(_.-)で3〜64文字で入力してください。';
        }
        if (mb_strlen($pass) < 8) {
            $errors[] = 'パスワードは8文字以上で設定してください。';
        }
        if ($pass !== $passConf) {
            $errors[] = '確認用パスワードが一致しません。';
        }
        if ($display === '') {
            $display = $username;
        }

        if (!empty($errors)) {
            (new View())->render('admin/auth/setup', [
                'title' => '初期セットアップ',
                'errors' => $errors,
                'old' => ['username' => $username, 'display_name' => $display],
            ], 'admin_minimal');
            return;
        }

        User::create($username, $pass, $display, 'admin');
        flash('success', '管理者を作成しました。ログインしてください。');
        redirect('/admin/login');
    }
}
