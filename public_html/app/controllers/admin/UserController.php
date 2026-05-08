<?php
namespace Controllers\Admin;

use Core\Auth;
use Core\Csrf;
use Core\View;
use Models\User;

class UserController
{
    public function __construct()
    {
        Auth::requireRole('admin');
    }

    public function index(): void
    {
        $users = User::all();
        (new View())->render('admin/users/index', [
            'title' => 'ユーザー管理',
            'users' => $users,
        ], 'admin');
    }

    public function createForm(): void
    {
        (new View())->render('admin/users/edit', [
            'title' => 'ユーザー新規作成',
            'user'  => ['id' => null, 'username' => '', 'display_name' => '', 'role' => 'editor'],
            'errors'=> [],
            'isNew' => true,
        ], 'admin');
    }

    public function store(): void
    {
        Csrf::verify();

        $username    = trim((string) ($_POST['username'] ?? ''));
        $displayName = trim((string) ($_POST['display_name'] ?? ''));
        $role        = in_array($_POST['role'] ?? '', ['admin', 'editor'], true) ? $_POST['role'] : 'editor';
        $password    = (string) ($_POST['password'] ?? '');
        $passwordConf= (string) ($_POST['password_confirm'] ?? '');

        $errors = $this->validate($username, $password, $passwordConf, null);
        if ($displayName === '') $displayName = $username;

        if (!empty($errors)) {
            (new View())->render('admin/users/edit', [
                'title' => 'ユーザー新規作成',
                'user'  => ['id' => null, 'username' => $username, 'display_name' => $displayName, 'role' => $role],
                'errors'=> $errors,
                'isNew' => true,
            ], 'admin');
            return;
        }

        User::create($username, $password, $displayName, $role);
        flash('success', 'ユーザーを作成しました。');
        redirect('/admin/users');
    }

    public function edit(int $id): void
    {
        $user = User::find($id);
        if (!$user) { $this->notFound(); return; }
        (new View())->render('admin/users/edit', [
            'title' => 'ユーザー編集',
            'user'  => $user,
            'errors'=> [],
            'isNew' => false,
        ], 'admin');
    }

    public function update(int $id): void
    {
        $user = User::find($id);
        if (!$user) { $this->notFound(); return; }

        Csrf::verify();
        $displayName = trim((string) ($_POST['display_name'] ?? ''));
        $role        = in_array($_POST['role'] ?? '', ['admin', 'editor'], true) ? $_POST['role'] : 'editor';
        $password    = (string) ($_POST['password'] ?? '');
        $passwordConf= (string) ($_POST['password_confirm'] ?? '');

        $errors = $this->validate($user['username'], $password, $passwordConf, $id, false);

        // 自分自身を editor に降格しようとする場合、admin が他に居なければ拒否
        if ((int) Auth::id() === $id && $role !== 'admin') {
            $remaining = $this->countOtherAdmins($id);
            if ($remaining === 0) {
                $errors[] = '他に admin 権限のユーザーがいないため、自身の権限を変更できません。';
            }
        }

        if (!empty($errors)) {
            (new View())->render('admin/users/edit', [
                'title' => 'ユーザー編集',
                'user'  => array_merge($user, ['display_name' => $displayName, 'role' => $role]),
                'errors'=> $errors,
                'isNew' => false,
            ], 'admin');
            return;
        }

        if ($displayName === '') $displayName = $user['username'];
        User::update($id, $displayName, $role, $password !== '' ? $password : null);
        flash('success', 'ユーザーを更新しました。');
        redirect('/admin/users');
    }

    public function destroy(int $id): void
    {
        Csrf::verify();
        $user = User::find($id);
        if (!$user) { $this->notFound(); return; }

        if ((int) Auth::id() === $id) {
            flash('danger', 'ログイン中の自分自身は削除できません。');
            redirect('/admin/users');
        }

        if ($user['role'] === 'admin' && $this->countOtherAdmins($id) === 0) {
            flash('danger', '最後の admin ユーザーは削除できません。');
            redirect('/admin/users');
        }

        User::delete($id);
        flash('success', 'ユーザーを削除しました。');
        redirect('/admin/users');
    }

    private function validate(string $username, string $password, string $passwordConf, ?int $excludeId, bool $requirePassword = true): array
    {
        $errors = [];
        if ($excludeId === null) {
            // 新規作成時のみ username を厳密に検査（編集時は不変）
            if (!preg_match('/\A[a-zA-Z0-9_.\-]{3,64}\z/', $username)) {
                $errors[] = 'ユーザー名は半角英数字・記号(_.-)で3〜64文字で入力してください。';
            } elseif (User::findByUsername($username) !== null) {
                $errors[] = 'そのユーザー名は既に使われています。';
            }
        }

        if ($requirePassword || $password !== '') {
            if (mb_strlen($password) < 8) {
                $errors[] = 'パスワードは8文字以上で設定してください。';
            }
            if ($password !== $passwordConf) {
                $errors[] = '確認用パスワードが一致しません。';
            }
        }

        return $errors;
    }

    private function countOtherAdmins(int $excludeId): int
    {
        $row = \Core\Database::fetchOne(
            "SELECT COUNT(*) AS c FROM users WHERE role = 'admin' AND id <> ?",
            [$excludeId]
        );
        return (int) ($row['c'] ?? 0);
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo '<!doctype html><meta charset="utf-8"><title>404</title><h1>404</h1>';
        exit;
    }
}
