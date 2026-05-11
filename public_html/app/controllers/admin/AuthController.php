<?php
namespace Controllers\Admin;

use Core\Auth;
use Core\Csrf;
use Core\View;
use Models\User;

class AuthController
{
    public function loginForm(): void
    {
        if (Auth::check()) {
            redirect('/admin/');
        }
        // 初期管理者がいなければ setup へ
        if (User::count() === 0) {
            redirect('/admin/setup');
        }

        (new View())->render('admin/auth/login', [
            'title' => 'ログイン',
        ], 'admin_minimal');
    }

    public function login(): void
    {
        Csrf::verify();

        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $ip = client_ip();

        if (Auth::isLockedOut($ip)) {
            flash('danger', '連続してログインに失敗しています。しばらく時間をおいてから再度お試しください。');
            redirect('/admin/login');
        }

        if ($username === '' || $password === '') {
            Auth::recordAttempt($ip, $username, false);
            flash('danger', 'ユーザー名とパスワードを入力してください。');
            redirect('/admin/login');
        }

        $user = User::findByUsername($username);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            // タイミング攻撃緩和のため微小スリープ
            usleep(random_int(100000, 400000));
            Auth::recordAttempt($ip, $username, false);
            flash('danger', 'ユーザー名またはパスワードが正しくありません。');
            redirect('/admin/login');
        }

        Auth::login($user);
        Auth::recordAttempt($ip, $username, true);
        User::recordLogin((int) $user['id']);

        $intended = $_SESSION['_intended'] ?? null;
        unset($_SESSION['_intended']);

        // リダイレクト直前にセッションを確実に保存（環境によっては Cookie が欠けるのを防ぐ）
        session_write_close();

        if ($intended && is_string($intended)) {
            header('Location: ' . $intended);
            exit;
        }
        redirect('/admin/');
    }

    public function logout(): void
    {
        Auth::logout();
        flash('info', 'ログアウトしました。');
        redirect('/admin/login');
    }
}
