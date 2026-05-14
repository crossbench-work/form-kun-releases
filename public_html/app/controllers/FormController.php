<?php
namespace Controllers;

use Core\Csrf;
use Core\FileStorage;
use Core\Mailer;
use Core\Validator;
use Core\View;
use Models\Form;
use Models\FormField;
use Models\Submission;

class FormController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function show(string $slug): void
    {
        $form = Form::findActiveBySlug($slug);
        if (!$form) {
            $this->notFound();
            return;
        }
        $fields = FormField::forForm((int) $form['id']);

        $this->view->render('front/form/show', [
            'title'      => $form['title'],
            'form'       => $form,
            'fields'     => $fields,
            'values'     => [],
            'errors'     => [],
        ], 'public');
    }

    public function confirm(string $slug): void
    {
        $form = Form::findActiveBySlug($slug);
        if (!$form) { $this->notFound(); return; }

        Csrf::verify();
        $fields = FormField::forForm((int) $form['id']);

        // バリデーション
        $validator = new Validator();
        $files = self::buildFileMap($fields);
        $result = $validator->validate($fields, $_POST, $files);

        if (!empty($result['errors'])) {
            $this->view->render('front/form/show', [
                'title'      => $form['title'],
                'form'       => $form,
                'fields'     => $fields,
                'values'     => $result['values'],
                'errors'     => $result['errors'],
            ], 'public');
            return;
        }

        // ファイルは確認画面では一時保管にせず、サーバ側に即保存（再送信対策）。
        // 共有レンタルサーバーで PHP-FPM の tmp_name 永続化が当てにならないため。
        $fileMetas = [];
        foreach ($files as $key => $entry) {
            if (!$entry || $entry['error'] === UPLOAD_ERR_NO_FILE) continue;
            $meta = FileStorage::store($entry);
            if ($meta) {
                $fileMetas[$key] = $meta;
            }
        }

        // セッションに値を保管
        $_SESSION['_pending_submission'] = [
            'form_id'    => (int) $form['id'],
            'values'     => $result['values'],
            'file_metas' => $fileMetas,
            'created_at' => time(),
        ];
        Csrf::rotate();

        $this->view->render('front/form/confirm', [
            'title'  => '入力内容の確認',
            'form'   => $form,
            'fields' => $fields,
            'values' => $result['values'],
            'fileMetas' => $fileMetas,
        ], 'public');
    }

    public function submit(string $slug): void
    {
        $form = Form::findActiveBySlug($slug);
        if (!$form) { $this->notFound(); return; }

        Csrf::verify();
        $pending = $_SESSION['_pending_submission'] ?? null;
        if (!$pending || (int) $pending['form_id'] !== (int) $form['id']) {
            // セッション切れ
            redirect('/f/' . $slug);
        }
        // 30分以上経過したら破棄
        if (time() - (int) $pending['created_at'] > 60 * 30) {
            unset($_SESSION['_pending_submission']);
            redirect('/f/' . $slug);
        }

        $fields = FormField::forForm((int) $form['id']);

        // 戻るで再編集
        if (!empty($_POST['_back'])) {
            $values = $pending['values'];
            // 確認画面でアップ済みのファイルは戻ったらクリアする
            foreach ($pending['file_metas'] ?? [] as $meta) {
                FileStorage::delete($meta['stored_path']);
            }
            unset($_SESSION['_pending_submission']);
            $this->view->render('front/form/show', [
                'title'      => $form['title'],
                'form'       => $form,
                'fields'     => $fields,
                'values'     => $values,
                'errors'     => [],
            ], 'public');
            return;
        }

        // 保存
        $sid = Submission::create(
            (int) $form['id'],
            $pending['values'],
            $pending['file_metas'] ?? [],
            client_ip(),
            $_SERVER['HTTP_USER_AGENT'] ?? ''
        );
        unset($_SESSION['_pending_submission']);
        Csrf::rotate();

        // メール送信
        $this->sendMails($form, $fields, $pending['values'], $pending['file_metas'] ?? []);

        $this->view->render('front/form/thanks', [
            'title' => '送信完了',
            'form'  => $form,
            'submissionId' => $sid,
        ], 'public');
    }

    /**
     * $_FILES の整形。
     */
    private static function buildFileMap(array $fields): array
    {
        $map = [];
        foreach ($fields as $f) {
            if ($f['type'] !== 'file') continue;
            $key = $f['field_key'];
            if (isset($_FILES[$key])) {
                $map[$key] = $_FILES[$key];
            }
        }
        return $map;
    }

    private function sendMails(array $form, array $fields, array $values, array $fileMetas): void
    {
        $context = [
            'submitted_at' => date('Y-m-d H:i:s'),
            'form_title'   => $form['title'],
            'ip'           => client_ip(),
        ];

        // 管理者通知
        $admins = $form['admin_emails'] ?? '';
        $admins = array_filter(array_map('trim', preg_split('/[,;]+/', (string) $admins) ?: []));
        if (!empty($admins)) {
            $subject = !empty($form['mail_subject_admin'])
                ? $form['mail_subject_admin']
                : '[' . $form['title'] . '] 新規送信';
            $template = !empty($form['mail_template_admin'])
                ? $form['mail_template_admin']
                : "フォーム「{{__form_title__}}」に新しい送信がありました。\n受信日時: {{__submitted_at__}}\nIP: {{__ip__}}\n\n{{__all__}}";
            $body = Mailer::renderTemplate($template, $fields, $values, $context);
            Mailer::send($admins, $subject, $body);
        }

        // 自動返信
        if (!empty($form['reply_enabled'])) {
            $to = $this->resolveReplyRecipient($fields, $values);
            if ($to !== null) {
                $subject  = (string) ($form['reply_subject'] ?? '送信ありがとうございました');
                $template = (string) ($form['reply_template'] ?? "お問い合わせいただきありがとうございました。\n以下の内容で受け付けました。\n\n{{__all__}}");
                $body = Mailer::renderTemplate($template, $fields, $values, $context);

                $headers = [];
                $fromEmail = trim((string) ($form['reply_from_field_key'] ?? ''));
                if ($fromEmail !== '' && filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
                    $headers['From'] = $fromEmail;
                    $headers['Reply-To'] = $fromEmail;
                }

                Mailer::send($to, $subject, $body, $headers);
            }
        }
    }

    private function resolveReplyRecipient(array $fields, array $values): ?string
    {
        foreach ($fields as $field) {
            if (($field['type'] ?? '') !== 'email') {
                continue;
            }

            $value = $values[$field['field_key']] ?? null;
            if (is_array($value)) {
                $value = reset($value);
            }

            if (is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return $value;
            }
        }

        return null;
    }

    private function notFound(): void
    {
        http_response_code(404);
        (new View())->render('front/404', ['title' => 'Not Found'], 'public');
    }
}
