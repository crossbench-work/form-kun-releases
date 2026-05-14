<?php
namespace Controllers\Admin;

use Core\Auth;
use Core\Csrf;
use Core\View;
use Models\AppSetting;

class MailSettingController
{
    public function __construct()
    {
        Auth::requireRole('admin');
    }

    public function edit(): void
    {
        (new View())->render('admin/settings/mail', [
            'title' => '通知設定',
            'settings' => AppSetting::currentMailSettings(),
            'errors' => [],
        ], 'admin');
    }

    public function update(): void
    {
        Csrf::verify();

        $settings = $this->collect();
        $errors = $this->validate($settings);

        if ($errors !== []) {
            $settings['smtp_password'] = '';
            (new View())->render('admin/settings/mail', [
                'title' => '通知設定',
                'settings' => $settings,
                'errors' => $errors,
            ], 'admin');
            return;
        }

        AppSetting::saveMailSettings($settings);
        flash('success', '通知設定を更新しました。');
        redirect('/admin/settings/mail');
    }

    /**
     * @return array<string, mixed>
     */
    private function collect(): array
    {
        $transport = (string) ($_POST['transport'] ?? 'mail');
        $smtpPort = trim((string) ($_POST['smtp_port'] ?? ''));
        $smtpTimeout = trim((string) ($_POST['smtp_timeout'] ?? ''));

        $current = AppSetting::currentMailSettings();

        return [
            'transport' => in_array($transport, ['mail', 'smtp'], true) ? $transport : 'mail',
            'from_email' => trim((string) ($_POST['from_email'] ?? '')),
            'from_name' => trim((string) ($_POST['from_name'] ?? '')),
            'smtp_enabled' => $transport === 'smtp' ? 1 : 0,
            'smtp_host' => trim((string) ($_POST['smtp_host'] ?? '')),
            'smtp_port' => $smtpPort !== '' ? $smtpPort : '25',
            'smtp_secure' => (string) ($_POST['smtp_secure'] ?? ''),
            'smtp_username' => trim((string) ($_POST['smtp_username'] ?? '')),
            'smtp_password' => (string) ($_POST['smtp_password'] ?? ''),
            'smtp_timeout' => $smtpTimeout !== '' ? $smtpTimeout : '10',
            'smtp_ehlo_host' => trim((string) ($_POST['smtp_ehlo_host'] ?? '')),
            'smtp_verify_peer' => !empty($_POST['smtp_verify_peer']) ? 1 : 0,
            'smtp_verify_peer_name' => !empty($_POST['smtp_verify_peer_name']) ? 1 : 0,
            'smtp_allow_self_signed' => !empty($_POST['smtp_allow_self_signed']) ? 1 : 0,
            'smtp_clear_password' => !empty($_POST['smtp_clear_password']) ? 1 : 0,
            'smtp_password_configured' => !empty($current['smtp_password_configured']) ? 1 : 0,
        ];
    }

    /**
     * @param array<string, mixed> $settings
     * @return string[]
     */
    private function validate(array $settings): array
    {
        $errors = [];

        if ($settings['from_email'] === '' || !filter_var((string) $settings['from_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = '差出人メールアドレスは有効なメールアドレスを入力してください。';
        }

        if (!in_array($settings['transport'], ['mail', 'smtp'], true)) {
            $errors[] = '送信方式が不正です。';
        }

        if ($settings['transport'] === 'smtp') {
            if ($settings['smtp_host'] === '') {
                $errors[] = 'SMTPホストは必須です。';
            }
            if (!ctype_digit((string) $settings['smtp_port']) || (int) $settings['smtp_port'] < 1 || (int) $settings['smtp_port'] > 65535) {
                $errors[] = 'SMTPポートは 1〜65535 の整数で入力してください。';
            }
            if (!in_array($settings['smtp_secure'], ['', 'ssl', 'tls'], true)) {
                $errors[] = '暗号化方式は「なし」「SSL」「STARTTLS」のいずれかを選択してください。';
            }
            if (!ctype_digit((string) $settings['smtp_timeout']) || (int) $settings['smtp_timeout'] < 1 || (int) $settings['smtp_timeout'] > 300) {
                $errors[] = 'SMTPタイムアウトは 1〜300 秒で入力してください。';
            }
            if (
                empty($settings['smtp_clear_password'])
                && !$settings['smtp_password_configured']
                && trim((string) $settings['smtp_username']) !== ''
                && trim((string) $settings['smtp_password']) === ''
            ) {
                $errors[] = 'SMTPユーザー名を使う場合は、初回保存時にSMTPパスワードも入力してください。';
            }
        }

        return $errors;
    }
}
