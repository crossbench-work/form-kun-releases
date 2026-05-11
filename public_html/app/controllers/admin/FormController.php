<?php
namespace Controllers\Admin;

use Core\Auth;
use Core\Csrf;
use Core\FieldKey;
use Core\View;
use Models\Form;
use Models\FormField;

class FormController
{
    public function __construct()
    {
        Auth::requireLogin();
    }

    public function index(): void
    {
        $forms = Form::all();
        (new View())->render('admin/forms/index', [
            'title' => 'フォーム一覧',
            'forms' => $forms,
        ], 'admin');
    }

    public function createForm(): void
    {
        (new View())->render('admin/forms/edit', [
            'title' => 'フォーム新規作成',
            'form'  => self::defaultForm(),
            'fields'=> [self::defaultField()],
            'errors'=> [],
            'isNew' => true,
        ], 'admin');
    }

    public function store(): void
    {
        Csrf::verify();
        $data = $this->collect();
        $errors = $this->validate($data, null);

        if (!empty($errors)) {
            (new View())->render('admin/forms/edit', [
                'title' => 'フォーム新規作成',
                'form'  => $data['form'],
                'fields'=> $data['fields'],
                'errors'=> $errors,
                'isNew' => true,
            ], 'admin');
            return;
        }

        $id = Form::create($data['form']);
        FormField::replaceForForm($id, $data['fields']);

        flash('success', 'フォームを作成しました。');
        redirect('/admin/forms/' . $id . '/edit');
    }

    public function edit(int $id): void
    {
        $form = Form::find($id);
        if (!$form) { $this->notFound(); return; }
        $fields = FormField::forForm($id);

        // 編集ビュー用に options をテキストに変換
        $editFields = array_map(function ($f) {
            $opts = json_decode((string) ($f['options_json'] ?? ''), true);
            $f['options'] = is_array($opts) ? implode("\n", $opts) : '';
            return $f;
        }, $fields);
        if (empty($editFields)) {
            $editFields = [self::defaultField()];
        }

        (new View())->render('admin/forms/edit', [
            'title' => 'フォーム編集',
            'form'  => $form,
            'fields'=> $editFields,
            'errors'=> [],
            'isNew' => false,
        ], 'admin');
    }

    public function update(int $id): void
    {
        $form = Form::find($id);
        if (!$form) { $this->notFound(); return; }

        Csrf::verify();
        $data = $this->collect();
        $errors = $this->validate($data, $id);

        if (!empty($errors)) {
            (new View())->render('admin/forms/edit', [
                'title' => 'フォーム編集',
                'form'  => array_merge($form, $data['form']),
                'fields'=> $data['fields'],
                'errors'=> $errors,
                'isNew' => false,
            ], 'admin');
            return;
        }

        Form::update($id, $data['form']);
        FormField::replaceForForm($id, $data['fields']);

        flash('success', 'フォームを更新しました。');
        redirect('/admin/forms/' . $id . '/edit');
    }

    public function destroy(int $id): void
    {
        $form = Form::find($id);
        if (!$form) { $this->notFound(); return; }

        Csrf::verify();
        Form::delete($id);
        flash('success', 'フォームを削除しました。');
        redirect('/admin/forms');
    }

    /**
     * POST から form / fields を組み立てる。
     */
    private function collect(): array
    {
        $form = [
            'slug'                 => trim((string) ($_POST['slug'] ?? '')),
            'title'                => trim((string) ($_POST['title'] ?? '')),
            'description'          => trim((string) ($_POST['description'] ?? '')),
            'thanks_message'       => trim((string) ($_POST['thanks_message'] ?? '')),
            'redirect_url'         => trim((string) ($_POST['redirect_url'] ?? '')),
            'admin_emails'         => trim((string) ($_POST['admin_emails'] ?? '')),
            'mail_subject_admin'   => trim((string) ($_POST['mail_subject_admin'] ?? '')),
            'mail_template_admin'  => (string) ($_POST['mail_template_admin'] ?? ''),
            'reply_enabled'        => !empty($_POST['reply_enabled']) ? 1 : 0,
            'reply_from_field_key' => trim((string) ($_POST['reply_from_field_key'] ?? '')),
            'reply_subject'        => trim((string) ($_POST['reply_subject'] ?? '')),
            'reply_template'       => (string) ($_POST['reply_template'] ?? ''),
            'captcha_enabled'      => !empty($_POST['captcha_enabled']) ? 1 : 0,
            'status'               => in_array($_POST['status'] ?? '', ['active','draft','archived'], true) ? $_POST['status'] : 'draft',
        ];

        $fields = [];
        $raw = $_POST['fields'] ?? [];
        if (is_array($raw)) {
            foreach ($raw as $f) {
                if (!is_array($f)) continue;
                if (!empty($f['_remove'])) continue;
                $key = trim((string) ($f['field_key'] ?? ''));
                if ($key === '') {
                    $key = FieldKey::generateUuidV4();
                }

                $patterns = [];
                if (!empty($f['regex_patterns']) && is_array($f['regex_patterns'])) {
                    foreach ($f['regex_patterns'] as $p) {
                        $p = trim((string) $p);
                        if ($p !== '' && $p !== '__custom__') {
                            $patterns[] = $p;
                        }
                    }
                }
                $regexStored = FormField::encodeRegexPatterns($patterns);

                $fields[] = [
                    'field_key'       => $key,
                    'label'           => trim((string) ($f['label'] ?? '')),
                    'type'            => in_array($f['type'] ?? '', FormField::ALLOWED_TYPES(), true) ? $f['type'] : 'text',
                    'required'        => !empty($f['required']) ? 1 : 0,
                    'max_length'      => isset($f['max_length']) && $f['max_length'] !== '' ? (int) $f['max_length'] : '',
                    'regex'           => $regexStored,
                    'help_text'       => trim((string) ($f['help_text'] ?? '')),
                    'placeholder'     => trim((string) ($f['placeholder'] ?? '')),
                    'options'         => (string) ($f['options'] ?? ''),
                ];
            }
        }
        if (empty($fields)) {
            $fields = [self::defaultField()];
        }

        return ['form' => $form, 'fields' => $fields];
    }

    private function validate(array $data, ?int $excludeId): array
    {
        $errors = [];
        $form = $data['form'];
        $fields = $data['fields'];

        if ($form['title'] === '') {
            $errors[] = 'タイトルは必須です。';
        }
        if (!preg_match('/\A[a-zA-Z0-9_\-]{1,64}\z/', $form['slug'])) {
            $errors[] = 'スラッグは半角英数字・ハイフン・アンダースコアで64文字以内にしてください。';
        } elseif (!Form::isSlugAvailable($form['slug'], $excludeId)) {
            $errors[] = '同じスラッグが既に使われています。';
        }
        if ($form['admin_emails'] !== '') {
            foreach (preg_split('/[,;]+/', $form['admin_emails']) as $em) {
                $em = trim($em);
                if ($em === '') continue;
                if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = '管理者通知メールアドレスに不正な形式があります: ' . $em;
                }
            }
        }
        if ($form['redirect_url'] !== '' && !filter_var($form['redirect_url'], FILTER_VALIDATE_URL)) {
            $errors[] = 'リダイレクトURLが正しくありません。';
        }

        if (count($fields) === 0) {
            $errors[] = '項目を1つ以上設定してください。';
        }

        $usedKeys = [];
        foreach ($fields as $i => $f) {
            $no = $i + 1;
            if ($f['label'] === '') {
                $errors[] = "項目{$no}: ラベルは必須です。";
            }
            if (!FieldKey::isValid($f['field_key'])) {
                $errors[] = "項目{$no}: フィールドキーは UUID 形式、または英字で始まる英数字・アンダースコア（最大64文字）にしてください。";
            } elseif (in_array($f['field_key'], $usedKeys, true)) {
                $errors[] = "項目{$no}: フィールドキー '{$f['field_key']}' が重複しています。";
            } else {
                $usedKeys[] = $f['field_key'];
            }
            if (in_array($f['type'], ['radio','checkbox','select'], true)) {
                $opts = FormField::parseOptions($f['options']);
                if (count($opts) === 0) {
                    $errors[] = "項目{$no}: 選択肢を1行に1つずつ入力してください。";
                }
            }
            $regexPatterns = FormField::decodeRegexPatterns(
                isset($f['regex']) && is_string($f['regex']) ? $f['regex'] : ''
            );
            foreach ($regexPatterns as $ri => $pat) {
                if ($pat !== '' && @preg_match($pat, '') === false) {
                    $errors[] = "項目{$no}: 正規表現（" . ($ri + 1) . "行目）が不正です。";
                }
            }
        }

        if (!empty($form['reply_enabled'])) {
            if (!in_array($form['reply_from_field_key'], $usedKeys, true)) {
                $errors[] = '自動返信先のフィールドキーは、項目に存在するキーを指定してください。';
            }
        }

        return $errors;
    }

    private static function defaultForm(): array
    {
        return [
            'id' => null,
            'slug' => '',
            'title' => '',
            'description' => '',
            'thanks_message' => '',
            'redirect_url' => '',
            'admin_emails' => '',
            'mail_subject_admin' => '',
            'mail_template_admin' => "フォーム「{{__form_title__}}」に新しい送信がありました。\n受信日時: {{__submitted_at__}}\nIP: {{__ip__}}\n\n{{__all__}}",
            'reply_enabled' => 0,
            'reply_from_field_key' => '',
            'reply_subject' => '送信ありがとうございました',
            'reply_template' => "お問い合わせいただきありがとうございました。\n以下の内容で受け付けました。\n\n{{__all__}}",
            'captcha_enabled' => 1,
            'status' => 'draft',
        ];
    }

    private static function defaultField(): array
    {
        return [
            'field_key' => FieldKey::generateUuidV4(),
            'label' => 'お名前',
            'type'  => 'text',
            'required' => 1,
            'max_length' => 100,
            'regex' => null,
            'help_text' => '',
            'placeholder' => '',
            'options' => '',
        ];
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo '<!doctype html><meta charset="utf-8"><title>404</title><h1>404</h1>';
        exit;
    }
}
