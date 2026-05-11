<?php
namespace Controllers\Admin;

use Core\Auth;
use Core\Csrf;
use Core\Mailer;
use Core\View;
use Models\PasswordReset;
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

    /** パスワード忘れ（メールで再設定） */
    public function forgotForm(): void
    {
        if (Auth::check()) {
            redirect('/admin/');
        }
        if (User::count() === 0) {
            redirect('/admin/setup');
        }
        (new View())->render('admin/auth/forgot', [
            'title' => 'パスワード再設定',
        ], 'admin_minimal');
    }

    public function forgotSubmit(): void
    {
        Csrf::verify();
        if (Auth::check()) {
            redirect('/admin/');
        }

        if (!$this->forgotThrottleOk()) {
            flash('danger', 'リクエストが多すぎます。しばらく時間をおいてから再度お試しください。');
            redirect('/admin/forgot-password');
        }

        $identifier = trim((string) ($_POST['identifier'] ?? ''));
        $genericMsg = '入力内容を確認しました。メールアドレスが登録されている場合は、再設定用のメールを送信しました。';

        if ($identifier === '') {
            flash('info', $genericMsg);
            redirect('/admin/login');
            return;
        }

        $user = User::findByUsernameOrEmail($identifier);

        // アカウント列挙を避けるため、存在しない／メール未登録でも同じ文言で終了
        if (!$user || empty($user['email'])) {
            flash('info', $genericMsg);
            redirect('/admin/login');
            return;
        }

        try {
            $token = PasswordReset::issueToken((int) $user['id']);
            $resetUrl = url('/admin/reset-password?token=' . rawurlencode($token));
            $subject = (string) config('mail.password_reset_subject', '[フォーム管理] パスワード再設定');
            $bodyTpl = (string) config(
                'mail.password_reset_body',
                "管理画面のパスワードを再設定するには、次のURLにアクセスしてください（1時間以内）。\n\n{{reset_url}}\n\n心当たりがない場合はこのメールを無視してください。"
            );
            $body = str_replace('{{reset_url}}', $resetUrl, $bodyTpl);
            Mailer::send($user['email'], $subject, $body);
        } catch (\Throwable $e) {
            error_log('[formkun] password reset mail failed: ' . $e->getMessage());
            flash('danger', 'メール送信に失敗しました。メール設定やサーバ環境を確認してください。');
            redirect('/admin/forgot-password');
            return;
        }

        flash('info', $genericMsg);
        redirect('/admin/login');
    }

    public function resetForm(): void
    {
        if (Auth::check()) {
            redirect('/admin/');
        }
        $token = (string) ($_GET['token'] ?? '');
        if ($token === '' || PasswordReset::findUserIdByToken($token) === null) {
            flash('danger', 'リンクの有効期限が切れているか、無効です。お手続きをやり直してください。');
            redirect('/admin/forgot-password');
            return;
        }
        (new View())->render('admin/auth/reset_password', [
            'title'     => '新しいパスワードを設定',
            'token'     => $token,
            'errors'    => [],
        ], 'admin_minimal');
    }

    public function resetSubmit(): void
    {
        Csrf::verify();
        if (Auth::check()) {
            redirect('/admin/');
        }

        $token = (string) ($_POST['token'] ?? '');
        $pass = (string) ($_POST['password'] ?? '');
        $passConf = (string) ($_POST['password_confirm'] ?? '');

        $uid = PasswordReset::findUserIdByToken($token);
        if ($uid === null) {
            flash('danger', 'リンクが無効です。お手続きを最初からやり直してください。');
            redirect('/admin/forgot-password');
            return;
        }

        $errors = [];
        if (mb_strlen($pass) < 8) {
            $errors[] = 'パスワードは8文字以上で設定してください。';
        }
        if ($pass !== $passConf) {
            $errors[] = '確認用パスワードが一致しません。';
        }

        if (!empty($errors)) {
            (new View())->render('admin/auth/reset_password', [
                'title'  => '新しいパスワードを設定',
                'token'  => $token,
                'errors' => $errors,
            ], 'admin_minimal');
            return;
        }

        User::setPassword($uid, $pass);
        PasswordReset::consumeToken($token);

        flash('success', 'パスワードを変更しました。ログインしてください。');
        redirect('/admin/login');
    }

    /**
     * 同一セッション単位で「パスワード忘れ」送信回数を抑える。
     */
    private function forgotThrottleOk(): bool
    {
        $max = (int) config('security.password_reset_forgot_max_per_hour', 5);
        $now = time();
        $bucket = $_SESSION['_pw_forgot_bucket'] ?? null;
        if (!is_array($bucket) || ($now - (int) ($bucket['start'] ?? 0)) > 3600) {
            $_SESSION['_pw_forgot_bucket'] = ['start' => $now, 'count' => 0];
            $bucket = $_SESSION['_pw_forgot_bucket'];
        }
        if ((int) ($bucket['count'] ?? 0) >= $max) {
            return false;
        }
        $_SESSION['_pw_forgot_bucket']['count'] = (int) $bucket['count'] + 1;
        return true;
    }
}
