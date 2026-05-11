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
            'form'  => self::prepareFormForEdit(self::defaultForm()),
            'fields'=> [],
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
        $form = self::prepareFormForEdit($form);
        $fields = FormField::forForm($id);

        // 編集ビュー用に options をテキストに変換
        $editFields = array_map(function ($f) {
            if (($f['type'] ?? '') === 'text_pair') {
                $pairLabels = \Models\FormField::decodeTextPairLabels($f['options_json'] ?? null);
                $pairPlaceholders = \Models\FormField::decodeTextPairPlaceholders($f['placeholder'] ?? null);
                $f['pair_label_left'] = $pairLabels['left_label'];
                $f['pair_label_right'] = $pairLabels['right_label'];
                $f['pair_placeholder_left'] = $pairPlaceholders['left_placeholder'];
                $f['pair_placeholder_right'] = $pairPlaceholders['right_placeholder'];
                $f['placeholder'] = '';
                $f['options'] = '';
                $f['checkbox_limit_enabled'] = 0;
                $f['checkbox_min_selections'] = '';
                $f['checkbox_max_selections'] = '';
            } else {
                if (($f['type'] ?? '') === 'checkbox') {
                    $checkboxSettings = \Models\FormField::decodeCheckboxSettings($f['options_json'] ?? null);
                    $f['options'] = implode("\n", $checkboxSettings['options']);
                    $f['checkbox_min_selections'] = $checkboxSettings['min_selections'];
                    $f['checkbox_max_selections'] = $checkboxSettings['max_selections'];
                    $f['checkbox_limit_enabled'] = ($checkboxSettings['min_selections'] !== '' || $checkboxSettings['max_selections'] !== '') ? 1 : 0;
                } else {
                    $opts = json_decode((string) ($f['options_json'] ?? ''), true);
                    $f['options'] = is_array($opts) && array_keys($opts) === range(0, count($opts) - 1)
                        ? implode("\n", $opts)
                        : '';
                    $f['checkbox_limit_enabled'] = 0;
                    $f['checkbox_min_selections'] = '';
                    $f['checkbox_max_selections'] = '';
                }
                $f['pair_label_left'] = '';
                $f['pair_label_right'] = '';
                $f['pair_placeholder_left'] = '';
                $f['pair_placeholder_right'] = '';
            }
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

    public function previewInput(): void
    {
        Csrf::verify();
        $data = $this->collect();

        (new View())->render('front/form/show', [
            'title' => '入力画面プレビュー',
            'form' => self::buildPreviewForm($data['form']),
            'fields' => self::preparePreviewFields($data['fields']),
            'values' => [],
            'errors' => [],
            'captchaQuestion' => null,
            'previewMode' => true,
        ], 'public');
    }

    public function previewConfirm(): void
    {
        Csrf::verify();
        $data = $this->collect();

        (new View())->render('front/form/confirm', [
            'title' => '確認画面プレビュー',
            'form' => self::buildPreviewForm($data['form']),
            'fields' => self::preparePreviewFields($data['fields']),
            'values' => [],
            'fileMetas' => [],
            'previewMode' => true,
        ], 'public');
    }

    public function previewThanks(): void
    {
        Csrf::verify();
        $data = $this->collect();

        (new View())->render('front/form/thanks', [
            'title' => '完了画面プレビュー',
            'form' => self::buildPreviewForm($data['form']),
            'previewMode' => true,
        ], 'public');
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
            'description'          => null,
            'show_title'           => !empty($_POST['show_title']) ? 1 : 0,
            'show_description'     => 0,
            'confirm_message'      => null,
            'thanks_message'       => null,
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

        $descriptionBlocks = self::collectContentBlocks('description_blocks', 'description');
        $form['description'] = encode_form_description_blocks($descriptionBlocks);
        $form['show_description'] = self::hasVisibleContentBlocks($descriptionBlocks) ? 1 : 0;
        $form['description_blocks'] = self::normalizeDescriptionBlocks($descriptionBlocks, true, 'description');
        $confirmBlocks = self::collectContentBlocks('confirm_blocks', 'confirm');
        $form['confirm_message'] = encode_form_description_blocks($confirmBlocks);
        $form['confirm_blocks'] = self::normalizeDescriptionBlocks($confirmBlocks, true, 'confirm');
        $thanksBlocks = self::collectContentBlocks('thanks_blocks', 'thanks');
        $form['thanks_message'] = encode_form_description_blocks($thanksBlocks);
        $form['thanks_blocks'] = self::normalizeDescriptionBlocks($thanksBlocks, true, 'thanks');

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

                $required = !empty($f['required']) ? 1 : 0;
                $type = in_array($f['type'] ?? '', FormField::ALLOWED_TYPES(), true) ? $f['type'] : 'text';
                $patterns = [];
                $singleRx = trim((string) ($f['regex_pattern'] ?? ''));
                if ($singleRx !== '' && $singleRx !== '__custom__') {
                    $patterns[] = $singleRx;
                }
                $regexStored = FormField::encodeRegexPatterns($patterns);
                if ($required !== 1 || in_array($type, ['radio', 'select'], true)) {
                    $regexStored = null;
                } elseif ($type === 'checkbox' && $singleRx !== '' && $singleRx !== '__custom__') {
                    $regexStored = null;
                }

                $placeholder = trim((string) ($f['placeholder'] ?? ''));
                if (in_array($type, ['radio', 'checkbox', 'select'], true)) {
                    $placeholder = '';
                }
                $maxLength = isset($f['max_length']) && $f['max_length'] !== '' ? (int) $f['max_length'] : '';
                if (in_array($type, ['radio', 'checkbox', 'select'], true)) {
                    $maxLength = '';
                }
                $checkboxLimitEnabled = ($type === 'checkbox' && $required === 1 && !empty($f['checkbox_limit_enabled'])) ? 1 : 0;
                $checkboxMinSelections = ($checkboxLimitEnabled && isset($f['checkbox_min_selections']) && $f['checkbox_min_selections'] !== '')
                    ? (int) $f['checkbox_min_selections']
                    : '';
                $checkboxMaxSelections = ($checkboxLimitEnabled && isset($f['checkbox_max_selections']) && $f['checkbox_max_selections'] !== '')
                    ? (int) $f['checkbox_max_selections']
                    : '';

                $fields[] = [
                    'field_key'       => $key,
                    'label'           => trim((string) ($f['label'] ?? '')),
                    'type'            => $type,
                    'required'        => $required,
                    'max_length'      => $maxLength,
                    'regex'           => $regexStored,
                    'help_text'       => trim((string) ($f['help_text'] ?? '')),
                    'placeholder'     => $placeholder,
                    'pair_label_left' => trim((string) ($f['pair_label_left'] ?? '')),
                    'pair_label_right'=> trim((string) ($f['pair_label_right'] ?? '')),
                    'pair_placeholder_left' => trim((string) ($f['pair_placeholder_left'] ?? '')),
                    'pair_placeholder_right'=> trim((string) ($f['pair_placeholder_right'] ?? '')),
                    'checkbox_limit_enabled' => $checkboxLimitEnabled,
                    'checkbox_min_selections' => $checkboxMinSelections,
                    'checkbox_max_selections' => $checkboxMaxSelections,
                    'options'         => (string) ($f['options'] ?? ''),
                ];
            }
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
                if ($f['type'] === 'checkbox' && !empty($f['checkbox_limit_enabled'])) {
                    $minSelections = $f['checkbox_min_selections'] !== '' ? (int) $f['checkbox_min_selections'] : 0;
                    $maxSelections = $f['checkbox_max_selections'] !== '' ? (int) $f['checkbox_max_selections'] : 0;
                    if ($f['checkbox_min_selections'] !== '' && $minSelections <= 0) {
                        $errors[] = "項目{$no}: 最小選択数は1以上で入力してください。";
                    }
                    if ($f['checkbox_max_selections'] !== '' && $maxSelections <= 0) {
                        $errors[] = "項目{$no}: 最大選択数は1以上で入力してください。";
                    }
                    if ($minSelections <= 0 && $maxSelections <= 0) {
                        $errors[] = "項目{$no}: 選択数を設定する場合は最小または最大を入力してください。";
                    }
                    if ($minSelections > 0 && $maxSelections > 0 && $minSelections > $maxSelections) {
                        $errors[] = "項目{$no}: 最小選択数は最大選択数以下にしてください。";
                    }
                    if ($minSelections > count($opts)) {
                        $errors[] = "項目{$no}: 最小選択数は選択肢数以下にしてください。";
                    }
                }
            } elseif ($f['type'] === 'text_pair') {
                if ($f['pair_label_left'] === '') {
                    $errors[] = "項目{$no}: 1つ目の入力欄の表示名を入力してください。";
                }
                if ($f['pair_label_right'] === '') {
                    $errors[] = "項目{$no}: 2つ目の入力欄の表示名を入力してください。";
                }
            }
            $regexPatterns = FormField::decodeRegexPatterns(
                isset($f['regex']) && is_string($f['regex']) ? $f['regex'] : ''
            );
            foreach ($regexPatterns as $pat) {
                if ($pat !== '' && @preg_match($pat, '') === false) {
                    $errors[] = "項目{$no}: 正規表現が不正です。";
                    break;
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
            'show_title' => 1,
            'show_description' => 1,
            'confirm_message' => '',
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

    /**
     * @param array<string, mixed> $form
     * @return array<string, mixed>
     */
    private static function prepareFormForEdit(array $form): array
    {
        $form['description_blocks'] = self::normalizeDescriptionBlocks(
            decode_form_description_blocks($form['description'] ?? '', !empty($form['show_description'])),
            false,
            'description'
        );
        $form['confirm_blocks'] = self::normalizeDescriptionBlocks(
            decode_form_description_blocks($form['confirm_message'] ?? '', true),
            false,
            'confirm'
        );
        $form['thanks_blocks'] = self::normalizeDescriptionBlocks(
            decode_form_description_blocks($form['thanks_message'] ?? '', true),
            false,
            'thanks'
        );

        return $form;
    }

    /**
     * @param array<int, array<string, mixed>> $blocks
     * @return array<int, array{id:string,html:string,position:int,visible:int}>
     */
    private static function normalizeDescriptionBlocks(array $blocks, bool $allowEmptyHtml = false, string $idPrefix = 'description'): array
    {
        $out = [];
        foreach ($blocks as $i => $block) {
            if (!is_array($block)) {
                continue;
            }
            $rawHtml = (string) ($block['html'] ?? '');
            $html = $allowEmptyHtml ? $rawHtml : sanitize_rich_text_html($rawHtml);
            if (!$allowEmptyHtml && $html === '') {
                continue;
            }
            $id = trim((string) ($block['id'] ?? ''));
            if ($id === '') {
                $id = $idPrefix . '-' . $i;
            }
            $out[] = [
                'id' => $id,
                'html' => $html,
                'position' => isset($block['position']) && is_numeric($block['position']) ? max(0, (int) $block['position']) : 0,
                'visible' => !empty($block['visible']) ? 1 : 0,
            ];
        }

        if ($out === []) {
            $out[] = [
                'id' => $idPrefix . '-0',
                'html' => '',
                'position' => 0,
                'visible' => 1,
            ];
        }

        return $out;
    }

    /**
     * @return array<int, array{id:string,html:string,position:int,visible:int}>
     */
    private static function collectContentBlocks(string $postKey, string $idPrefix): array
    {
        $blocks = [];
        $rawBlocks = $_POST[$postKey] ?? [];
        if (!is_array($rawBlocks)) {
            return $blocks;
        }

        foreach ($rawBlocks as $i => $block) {
            if (!is_array($block)) {
                continue;
            }
            $blocks[] = [
                'id' => trim((string) ($block['id'] ?? '')) !== '' ? trim((string) $block['id']) : $idPrefix . '-' . $i,
                'html' => (string) ($block['html'] ?? ''),
                'position' => isset($block['position']) && is_numeric($block['position']) ? max(0, (int) $block['position']) : 0,
                'visible' => !empty($block['visible']) ? 1 : 0,
            ];
        }

        return $blocks;
    }

    private static function hasVisibleContentBlocks(array $blocks): bool
    {
        foreach ($blocks as $block) {
            if (!empty($block['visible']) && sanitize_rich_text_html($block['html'] ?? '') !== '') {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<string, mixed> $form
     * @return array<string, mixed>
     */
    private static function buildPreviewForm(array $form): array
    {
        return array_merge($form, [
            'slug' => trim((string) ($form['slug'] ?? '')) !== '' ? (string) $form['slug'] : '__preview__',
            'captcha_enabled' => 0,
            'redirect_url' => '',
        ]);
    }

    /**
     * @param array<int, array<string, mixed>> $fields
     * @return array<int, array<string, mixed>>
     */
    private static function preparePreviewFields(array $fields): array
    {
        $previewFields = [];
        foreach ($fields as $i => $field) {
            $optionsJson = null;
            if (in_array($field['type'], ['radio', 'checkbox', 'select'], true) && !empty($field['options'])) {
                $options = FormField::parseOptions($field['options']);
                if ($field['type'] === 'checkbox') {
                    $optionsJson = FormField::encodeCheckboxSettings(
                        $options,
                        $field['checkbox_min_selections'] ?? '',
                        $field['checkbox_max_selections'] ?? ''
                    );
                } else {
                    $optionsJson = json_encode($options, JSON_UNESCAPED_UNICODE);
                }
            } elseif (($field['type'] ?? '') === 'text_pair') {
                $optionsJson = FormField::encodeTextPairLabels(
                    (string) ($field['pair_label_left'] ?? ''),
                    (string) ($field['pair_label_right'] ?? '')
                );
            }

            $placeholder = isset($field['placeholder']) && $field['placeholder'] !== '' ? (string) $field['placeholder'] : null;
            if (in_array($field['type'], ['radio', 'checkbox', 'select'], true)) {
                $placeholder = null;
            } elseif (($field['type'] ?? '') === 'text_pair') {
                $placeholder = FormField::encodeTextPairPlaceholders(
                    (string) ($field['pair_placeholder_left'] ?? ''),
                    (string) ($field['pair_placeholder_right'] ?? '')
                );
            }

            $previewFields[] = [
                'id' => $i + 1,
                'form_id' => 0,
                'sort_order' => $i + 1,
                'field_key' => (string) ($field['field_key'] ?? ''),
                'label' => (string) ($field['label'] ?? ''),
                'type' => (string) ($field['type'] ?? 'text'),
                'required' => !empty($field['required']) ? 1 : 0,
                'max_length' => isset($field['max_length']) && $field['max_length'] !== '' ? (int) $field['max_length'] : null,
                'regex' => isset($field['regex']) && $field['regex'] !== '' ? (string) $field['regex'] : null,
                'help_text' => isset($field['help_text']) && $field['help_text'] !== '' ? (string) $field['help_text'] : null,
                'placeholder' => $placeholder,
                'options_json' => $optionsJson,
            ];
        }

        return $previewFields;
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
            'pair_label_left' => '',
            'pair_label_right' => '',
            'pair_placeholder_left' => '',
            'pair_placeholder_right' => '',
            'checkbox_limit_enabled' => 0,
            'checkbox_min_selections' => '',
            'checkbox_max_selections' => '',
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
