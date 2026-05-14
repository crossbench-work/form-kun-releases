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
        $formCount = Form::count();
        $maxForms = effective_max_forms();
        (new View())->render('admin/forms/index', [
            'title'          => 'フォーム一覧',
            'forms'          => $forms,
            'form_count'     => $formCount,
            'max_forms'      => $maxForms,
            'at_form_limit'  => $formCount >= $maxForms,
        ], 'admin');
    }

    public function createForm(): void
    {
        if (Form::count() >= effective_max_forms()) {
            flash('danger', 'フォームの作成上限（' . effective_max_forms() . ' 件）に達しています。設定から上限を変更するか、不要なフォームを削除してください。');
            redirect('/admin/forms');
        }

        (new View())->render('admin/forms/edit', [
            'title' => 'フォーム新規作成',
            'form'  => self::prepareFormForEdit(self::defaultForm()),
            'fields'=> [self::defaultField()],
            'errors'=> [],
            'isNew' => true,
        ], 'admin');
    }

    public function store(): void
    {
        Csrf::verify();

        if (Form::count() >= effective_max_forms()) {
            flash('danger', 'フォームの作成上限（' . effective_max_forms() . ' 件）に達しています。設定から上限を変更するか、不要なフォームを削除してください。');
            redirect('/admin/forms');
        }

        $data = $this->collect();
        $errors = $this->validate($data, null);

        if (!empty($errors)) {
            (new View())->render('admin/forms/edit', [
                'title' => 'フォーム新規作成',
                'form'  => self::prepareFormForEdit($data['form']),
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
        $fields = FormField::forForm($id, true);

        // 編集ビュー用に options をテキストに変換
        $editFields = array_map(function ($f) {
            if (($f['type'] ?? '') === 'text_multi') {
                $f['multi_text_items'] = (string) ($f['options_json'] ?? '');
                $f['placeholder'] = '';
                $f['options'] = '';
                $f['checkbox_limit_enabled'] = 0;
                $f['checkbox_min_selections'] = '';
                $f['checkbox_max_selections'] = '';
                $f['pair_label_left'] = '';
                $f['pair_label_right'] = '';
                $f['pair_placeholder_left'] = '';
                $f['pair_placeholder_right'] = '';
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
                $f['multi_text_items'] = '';
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
        $data['form'] = self::mergeStoredMailPassword($data['form'], $form);
        $errors = $this->validate($data, $id);

        if (!empty($errors)) {
            (new View())->render('admin/forms/edit', [
                'title' => 'フォーム編集',
                'form'  => self::prepareFormForEdit(array_merge($form, $data['form'])),
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
            'input_page_title'     => trim((string) ($_POST['input_page_title'] ?? '')),
            'show_input_page_title'=> !empty($_POST['show_input_page_title']) ? 1 : 0,
            'description'          => null,
            'show_title'           => 0,
            'show_description'     => 0,
            'confirm_page_title'   => trim((string) ($_POST['confirm_page_title'] ?? '')),
            'show_confirm_page_title' => !empty($_POST['show_confirm_page_title']) ? 1 : 0,
            'confirm_button_text'  => trim((string) ($_POST['confirm_button_text'] ?? '')),
            'submit_button_text'   => trim((string) ($_POST['submit_button_text'] ?? '')),
            'back_button_text'     => trim((string) ($_POST['back_button_text'] ?? '')),
            'confirm_message'      => null,
            'thanks_page_title'    => trim((string) ($_POST['thanks_page_title'] ?? '')),
            'show_thanks_page_title' => !empty($_POST['show_thanks_page_title']) ? 1 : 0,
            'thanks_message'       => null,
            'thanks_button_text'   => trim((string) ($_POST['thanks_button_text'] ?? '')),
            'thanks_button_url'    => trim((string) ($_POST['thanks_button_url'] ?? '')),
            'admin_emails'         => trim((string) ($_POST['admin_emails'] ?? '')),
            'mail_subject_admin'   => trim((string) ($_POST['mail_subject_admin'] ?? '')),
            'mail_template_admin'  => (string) ($_POST['mail_template_admin'] ?? ''),
            'mail_transport'       => in_array($_POST['mail_transport'] ?? '', ['mail', 'smtp'], true) ? (string) $_POST['mail_transport'] : 'mail',
            'mail_from_email'      => trim((string) ($_POST['mail_from_email'] ?? '')),
            'mail_from_name'       => trim((string) ($_POST['mail_from_name'] ?? '')),
            'mail_smtp_host'       => trim((string) ($_POST['mail_smtp_host'] ?? '')),
            'mail_smtp_port'       => trim((string) ($_POST['mail_smtp_port'] ?? '')),
            'mail_smtp_secure'     => (string) ($_POST['mail_smtp_secure'] ?? ''),
            'mail_smtp_username'   => trim((string) ($_POST['mail_smtp_username'] ?? '')),
            'mail_smtp_password'   => (string) ($_POST['mail_smtp_password'] ?? ''),
            'mail_smtp_timeout'    => trim((string) ($_POST['mail_smtp_timeout'] ?? '')),
            'mail_smtp_ehlo_host'  => trim((string) ($_POST['mail_smtp_ehlo_host'] ?? '')),
            'mail_smtp_verify_peer' => !empty($_POST['mail_smtp_verify_peer']) ? 1 : 0,
            'mail_smtp_verify_peer_name' => !empty($_POST['mail_smtp_verify_peer_name']) ? 1 : 0,
            'mail_smtp_allow_self_signed' => !empty($_POST['mail_smtp_allow_self_signed']) ? 1 : 0,
            'mail_smtp_clear_password' => !empty($_POST['mail_smtp_clear_password']) ? 1 : 0,
            'reply_enabled'        => !empty($_POST['reply_enabled']) ? 1 : 0,
            'reply_from_field_key' => trim((string) ($_POST['reply_from_field_key'] ?? '')),
            'reply_subject'        => trim((string) ($_POST['reply_subject'] ?? '')),
            'reply_template'       => (string) ($_POST['reply_template'] ?? ''),
            'status'               => in_array($_POST['status'] ?? '', ['active','draft','archived'], true) ? $_POST['status'] : 'draft',
        ];
        if ($form['confirm_button_text'] === '') {
            $form['confirm_button_text'] = '入力内容を確認する';
        }
        if ($form['input_page_title'] === '') {
            $form['input_page_title'] = $form['title'];
        }
        if (!isset($form['show_input_page_title'])) {
            $form['show_input_page_title'] = 1;
        }
        if ($form['confirm_page_title'] === '') {
            $form['confirm_page_title'] = '入力内容のご確認';
        }
        if (!isset($form['show_confirm_page_title'])) {
            $form['show_confirm_page_title'] = 1;
        }
        if ($form['submit_button_text'] === '') {
            $form['submit_button_text'] = '送信する';
        }
        if ($form['back_button_text'] === '') {
            $form['back_button_text'] = '入力に戻る';
        }
        if ($form['thanks_page_title'] === '') {
            $form['thanks_page_title'] = '送信が完了しました';
        }
        if (!isset($form['show_thanks_page_title'])) {
            $form['show_thanks_page_title'] = 1;
        }
        if ($form['thanks_button_text'] === '') {
            $form['thanks_button_text'] = 'トップへ戻る';
        }
        if ($form['thanks_button_url'] === '') {
            $form['thanks_button_url'] = '/';
        }
        if ($form['mail_smtp_port'] === '') {
            $form['mail_smtp_port'] = '25';
        }
        if ($form['mail_smtp_timeout'] === '') {
            $form['mail_smtp_timeout'] = '10';
        }

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
                $type = FormField::sanitizeType((string) ($f['type'] ?? 'text_multi'));
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
                if (in_array($type, ['radio', 'checkbox', 'select', 'text_multi'], true)) {
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
                $helpTextRaw = trim((string) ($f['help_text'] ?? ''));
                $helpTextIsHtml = !empty($f['help_text_is_html']);
                $helpText = $helpTextIsHtml ? sanitize_rich_text_html($helpTextRaw) : $helpTextRaw;

                $fields[] = [
                    'field_key'       => $key,
                    'label'           => trim((string) ($f['label'] ?? '')),
                    'visible'         => !empty($f['visible']) ? 1 : 0,
                    'type'            => $type,
                    'required'        => $required,
                    'max_length'      => $maxLength,
                    'regex'           => $regexStored,
                    'help_text'       => $helpText,
                    'placeholder'     => $placeholder,
                    'pair_label_left' => trim((string) ($f['pair_label_left'] ?? '')),
                    'pair_label_right'=> trim((string) ($f['pair_label_right'] ?? '')),
                    'pair_placeholder_left' => trim((string) ($f['pair_placeholder_left'] ?? '')),
                    'pair_placeholder_right'=> trim((string) ($f['pair_placeholder_right'] ?? '')),
                    'multi_text_items' => (string) ($f['multi_text_items'] ?? ''),
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
        if (mb_strlen($form['input_page_title']) > 200) {
            $errors[] = '入力画面のタイトルは200文字以内にしてください。';
        }
        if (mb_strlen($form['confirm_page_title']) > 200) {
            $errors[] = '確認画面のタイトルは200文字以内にしてください。';
        }
        if (!preg_match('/\A[a-zA-Z0-9_\-]{1,64}\z/', $form['slug'])) {
            $errors[] = 'スラッグは半角英数字・ハイフン・アンダースコアで64文字以内にしてください。';
        } elseif (!Form::isSlugAvailable($form['slug'], $excludeId)) {
            $errors[] = '同じスラッグが既に使われています。';
        }
        if (mb_strlen($form['confirm_button_text']) > 200) {
            $errors[] = 'ボタンテキストは200文字以内にしてください。';
        }
        if (mb_strlen($form['submit_button_text']) > 200) {
            $errors[] = '確認画面の送信ボタンテキストは200文字以内にしてください。';
        }
        if (mb_strlen($form['back_button_text']) > 200) {
            $errors[] = '確認画面の戻るボタンテキストは200文字以内にしてください。';
        }
        if (mb_strlen($form['thanks_button_text']) > 200) {
            $errors[] = '完了画面のボタンテキストは200文字以内にしてください。';
        }
        if (mb_strlen($form['thanks_page_title']) > 200) {
            $errors[] = '完了画面のタイトルは200文字以内にしてください。';
        }
        if (mb_strlen($form['thanks_button_url']) > 500) {
            $errors[] = '完了画面のボタンURLは500文字以内にしてください。';
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
        if (!is_form_button_url($form['thanks_button_url'])) {
            $errors[] = '完了画面のボタンURLは http:// / https:// で始まるURL、または / から始まるパスを指定してください。';
        }
        if ($form['mail_from_email'] === '' || !filter_var($form['mail_from_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = '通知設定の差出人メールアドレスは有効なメールアドレスを入力してください。';
        }
        if (!in_array($form['mail_transport'], ['mail', 'smtp'], true)) {
            $errors[] = '通知設定の送信方式が不正です。';
        }
        if ($form['mail_transport'] === 'smtp') {
            if ($form['mail_smtp_host'] === '') {
                $errors[] = '通知設定の SMTPホストは必須です。';
            }
            if (!ctype_digit((string) $form['mail_smtp_port']) || (int) $form['mail_smtp_port'] < 1 || (int) $form['mail_smtp_port'] > 65535) {
                $errors[] = '通知設定の SMTPポートは 1〜65535 の整数で入力してください。';
            }
            if (!in_array($form['mail_smtp_secure'], ['', 'ssl', 'tls'], true)) {
                $errors[] = '通知設定の暗号化方式は「なし」「SSL」「STARTTLS」のいずれかを選択してください。';
            }
            if (!ctype_digit((string) $form['mail_smtp_timeout']) || (int) $form['mail_smtp_timeout'] < 1 || (int) $form['mail_smtp_timeout'] > 300) {
                $errors[] = '通知設定の SMTPタイムアウトは 1〜300 秒で入力してください。';
            }
            if (
                trim((string) $form['mail_smtp_username']) !== ''
                && trim((string) $form['mail_smtp_password']) === ''
            ) {
                $errors[] = '通知設定で SMTPユーザー名を使う場合は、SMTPパスワードも入力してください。';
            }
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
            } elseif ($f['type'] === 'text_multi') {
                $decodedItems = json_decode((string) ($f['multi_text_items'] ?? ''), true);
                $multiTextItems = [];
                if (is_array($decodedItems)) {
                    foreach ($decodedItems as $item) {
                        if (is_string($item)) {
                            $multiTextItems[] = [
                                'label' => trim($item),
                                'placeholder' => '',
                            ];
                            continue;
                        }
                        if (!is_array($item)) {
                            continue;
                        }
                        $multiTextItems[] = [
                            'label' => trim((string) ($item['label'] ?? '')),
                            'placeholder' => trim((string) ($item['placeholder'] ?? '')),
                        ];
                    }
                }
                if (count($multiTextItems) === 0) {
                    $errors[] = "項目{$no}: 複数入力の入力欄を1つ以上設定してください。";
                }
                foreach ($multiTextItems as $itemIndex => $item) {
                    if (count($multiTextItems) > 1 && ($item['label'] ?? '') === '') {
                        $displayIndex = $itemIndex + 1;
                        $errors[] = "項目{$no}: {$displayIndex}つ目の入力欄の表示名を入力してください。";
                    }
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
            $replyFieldKey = $form['reply_from_field_key'];
            $replyField = null;
            foreach ($fields as $field) {
                if (($field['field_key'] ?? '') === $replyFieldKey) {
                    $replyField = $field;
                    break;
                }
            }
            if ($replyField === null || ($replyField['type'] ?? '') !== 'email') {
                $errors[] = '送信先のフィールドキーには、項目に存在するメール型項目のフィールドキーを指定してください。';
            }
        }

        return $errors;
    }

    private static function defaultForm(): array
    {
        return [
            'id' => null,
            'slug' => FieldKey::generateUuidV4(),
            'title' => '',
            'input_page_title' => '',
            'show_input_page_title' => 1,
            'description' => '',
            'show_title' => 0,
            'show_description' => 1,
            'confirm_page_title' => '入力内容のご確認',
            'show_confirm_page_title' => 1,
            'confirm_button_text' => '入力内容を確認する',
            'submit_button_text' => '送信する',
            'back_button_text' => '入力に戻る',
            'confirm_message' => '',
            'thanks_page_title' => '送信が完了しました',
            'show_thanks_page_title' => 1,
            'thanks_message' => '',
            'thanks_button_text' => 'トップへ戻る',
            'thanks_button_url' => '/',
            'admin_emails' => '',
            'mail_subject_admin' => '',
            'mail_template_admin' => "フォーム「{{__form_title__}}」に新しい送信がありました。\n受信日時: {{__submitted_at__}}\nIP: {{__ip__}}\n\n{{__all__}}",
            'mail_transport' => (string) config('mail.transport', 'mail'),
            'mail_from_email' => (string) config('mail.from_email', 'noreply@example.com'),
            'mail_from_name' => (string) config('mail.from_name', ''),
            'mail_smtp_host' => (string) config('mail.smtp.host', ''),
            'mail_smtp_port' => (string) config('mail.smtp.port', 25),
            'mail_smtp_secure' => (string) config('mail.smtp.secure', ''),
            'mail_smtp_username' => (string) config('mail.smtp.username', ''),
            'mail_smtp_password' => '',
            'mail_smtp_timeout' => (string) config('mail.smtp.timeout', 10),
            'mail_smtp_ehlo_host' => (string) config('mail.smtp.ehlo_host', ''),
            'mail_smtp_verify_peer' => !empty(config('mail.smtp.verify_peer', true)) ? 1 : 0,
            'mail_smtp_verify_peer_name' => !empty(config('mail.smtp.verify_peer_name', true)) ? 1 : 0,
            'mail_smtp_allow_self_signed' => !empty(config('mail.smtp.allow_self_signed', false)) ? 1 : 0,
            'mail_smtp_password_configured' => 0,
            'mail_smtp_clear_password' => 0,
            'reply_enabled' => 0,
            'reply_from_field_key' => '',
            'reply_subject' => '送信ありがとうございました',
            'reply_template' => "お問い合わせいただきありがとうございました。\n以下の内容で受け付けました。\n\n{{__all__}}",
            'status' => 'draft',
        ];
    }

    /**
     * @param array<string, mixed> $form
     * @return array<string, mixed>
     */
    private static function prepareFormForEdit(array $form): array
    {
        $form['input_page_title'] = trim((string) ($form['input_page_title'] ?? '')) !== ''
            ? (string) $form['input_page_title']
            : trim((string) ($form['title'] ?? ''));
        $form['show_input_page_title'] = !array_key_exists('show_input_page_title', $form) || !empty($form['show_input_page_title']) ? 1 : 0;
        $form['confirm_page_title'] = trim((string) ($form['confirm_page_title'] ?? '')) !== ''
            ? (string) $form['confirm_page_title']
            : '入力内容のご確認';
        $form['show_confirm_page_title'] = !array_key_exists('show_confirm_page_title', $form) || !empty($form['show_confirm_page_title']) ? 1 : 0;
        $form['confirm_button_text'] = trim((string) ($form['confirm_button_text'] ?? '')) !== ''
            ? (string) $form['confirm_button_text']
            : '入力内容を確認する';
        $form['submit_button_text'] = trim((string) ($form['submit_button_text'] ?? '')) !== ''
            ? (string) $form['submit_button_text']
            : '送信する';
        $form['back_button_text'] = trim((string) ($form['back_button_text'] ?? '')) !== ''
            ? (string) $form['back_button_text']
            : '入力に戻る';
        $form['thanks_page_title'] = trim((string) ($form['thanks_page_title'] ?? '')) !== ''
            ? (string) $form['thanks_page_title']
            : '送信が完了しました';
        $form['show_thanks_page_title'] = !array_key_exists('show_thanks_page_title', $form) || !empty($form['show_thanks_page_title']) ? 1 : 0;
        $form['thanks_button_text'] = trim((string) ($form['thanks_button_text'] ?? '')) !== ''
            ? (string) $form['thanks_button_text']
            : 'トップへ戻る';
        $form['thanks_button_url'] = trim((string) ($form['thanks_button_url'] ?? '')) !== ''
            ? (string) $form['thanks_button_url']
            : '/';
        $form['mail_transport'] = in_array(($form['mail_transport'] ?? null), ['mail', 'smtp'], true)
            ? (string) $form['mail_transport']
            : (string) config('mail.transport', 'mail');
        $form['mail_from_email'] = trim((string) ($form['mail_from_email'] ?? '')) !== ''
            ? (string) $form['mail_from_email']
            : (string) config('mail.from_email', 'noreply@example.com');
        $form['mail_from_name'] = array_key_exists('mail_from_name', $form)
            ? (string) ($form['mail_from_name'] ?? '')
            : (string) config('mail.from_name', '');
        $form['mail_smtp_host'] = array_key_exists('mail_smtp_host', $form)
            ? (string) ($form['mail_smtp_host'] ?? '')
            : (string) config('mail.smtp.host', '');
        $form['mail_smtp_port'] = trim((string) ($form['mail_smtp_port'] ?? '')) !== ''
            ? (string) $form['mail_smtp_port']
            : (string) config('mail.smtp.port', 25);
        $form['mail_smtp_secure'] = in_array(($form['mail_smtp_secure'] ?? null), ['', 'ssl', 'tls'], true)
            ? (string) ($form['mail_smtp_secure'] ?? '')
            : (string) config('mail.smtp.secure', '');
        $form['mail_smtp_username'] = array_key_exists('mail_smtp_username', $form)
            ? (string) ($form['mail_smtp_username'] ?? '')
            : (string) config('mail.smtp.username', '');
        $form['mail_smtp_timeout'] = trim((string) ($form['mail_smtp_timeout'] ?? '')) !== ''
            ? (string) $form['mail_smtp_timeout']
            : (string) config('mail.smtp.timeout', 10);
        $form['mail_smtp_ehlo_host'] = array_key_exists('mail_smtp_ehlo_host', $form)
            ? (string) ($form['mail_smtp_ehlo_host'] ?? '')
            : (string) config('mail.smtp.ehlo_host', '');
        $form['mail_smtp_verify_peer'] = !array_key_exists('mail_smtp_verify_peer', $form) || !empty($form['mail_smtp_verify_peer']) ? 1 : 0;
        $form['mail_smtp_verify_peer_name'] = !array_key_exists('mail_smtp_verify_peer_name', $form) || !empty($form['mail_smtp_verify_peer_name']) ? 1 : 0;
        $form['mail_smtp_allow_self_signed'] = !empty($form['mail_smtp_allow_self_signed']) ? 1 : 0;
        $form['mail_smtp_password_configured'] = trim((string) ($form['mail_smtp_password'] ?? '')) !== '' ? 1 : 0;
        $form['mail_smtp_password'] = '';
        $form['mail_smtp_clear_password'] = !empty($form['mail_smtp_clear_password']) ? 1 : 0;
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
     * @param array<string, mixed> $submittedForm
     * @param array<string, mixed> $storedForm
     * @return array<string, mixed>
     */
    private static function mergeStoredMailPassword(array $submittedForm, array $storedForm): array
    {
        if (!empty($submittedForm['mail_smtp_clear_password'])) {
            $submittedForm['mail_smtp_password'] = '';
            return $submittedForm;
        }

        if (trim((string) ($submittedForm['mail_smtp_password'] ?? '')) === '') {
            $submittedForm['mail_smtp_password'] = (string) ($storedForm['mail_smtp_password'] ?? '');
        }

        return $submittedForm;
    }

    /**
     * @param array<int, array<string, mixed>> $blocks
     * @return array<int, array{id:string,label:string,html:string,position:int,visible:int,is_html:int}>
     */
    private static function normalizeDescriptionBlocks(array $blocks, bool $allowEmptyHtml = false, string $idPrefix = 'description'): array
    {
        $out = [];
        foreach ($blocks as $i => $block) {
            if (!is_array($block)) {
                continue;
            }
            $rawHtml = trim((string) ($block['html'] ?? ''));
            $isHtml = array_key_exists('is_html', $block)
                ? !empty($block['is_html'])
                : preg_match('/<\s*\/?[a-zA-Z][^>]*>/', $rawHtml) === 1;
            $html = $allowEmptyHtml ? $rawHtml : ($isHtml ? sanitize_rich_text_html($rawHtml) : $rawHtml);
            if (!$allowEmptyHtml && $html === '') {
                continue;
            }
            $id = trim((string) ($block['id'] ?? ''));
            if ($id === '') {
                $id = $idPrefix . '-' . $i;
            }
            $out[] = [
                'id' => $id,
                'label' => trim((string) ($block['label'] ?? '')),
                'html' => $html,
                'position' => isset($block['position']) && is_numeric($block['position']) ? max(0, (int) $block['position']) : 0,
                'visible' => !empty($block['visible']) ? 1 : 0,
                'is_html' => $isHtml ? 1 : 0,
            ];
        }

        if ($out === []) {
            $out[] = [
                'id' => $idPrefix . '-0',
                'label' => '',
                'html' => '',
                'position' => 0,
                'visible' => 1,
                'is_html' => 0,
            ];
        }

        return $out;
    }

    /**
     * @return array<int, array{id:string,label:string,html:string,position:int,visible:int,is_html:int}>
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
                'label' => trim((string) ($block['label'] ?? '')),
                'html' => (string) ($block['html'] ?? ''),
                'position' => isset($block['position']) && is_numeric($block['position']) ? max(0, (int) $block['position']) : 0,
                'visible' => !empty($block['visible']) ? 1 : 0,
                'is_html' => !empty($block['is_html']) ? 1 : 0,
            ];
        }

        return $blocks;
    }

    private static function hasVisibleContentBlocks(array $blocks): bool
    {
        foreach ($blocks as $block) {
            $content = !empty($block['is_html'])
                ? sanitize_rich_text_html($block['html'] ?? '')
                : trim((string) ($block['html'] ?? ''));
            if (!empty($block['visible']) && $content !== '') {
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
            } elseif (($field['type'] ?? '') === 'text_multi') {
                $optionsJson = FormField::encodeTextMultiItems(
                    FormField::decodeTextMultiItems($field['multi_text_items'] ?? null)
                );
            }

            $placeholder = isset($field['placeholder']) && $field['placeholder'] !== '' ? (string) $field['placeholder'] : null;
            if (in_array($field['type'], ['radio', 'checkbox', 'select', 'text_multi'], true)) {
                $placeholder = null;
            }

            $previewFields[] = [
                'id' => $i + 1,
                'form_id' => 0,
                'sort_order' => $i + 1,
                'field_key' => (string) ($field['field_key'] ?? ''),
                'label' => (string) ($field['label'] ?? ''),
                'visible' => !array_key_exists('visible', $field) || !empty($field['visible']) ? 1 : 0,
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
            'visible' => 1,
            'type'  => 'text_multi',
            'required' => 1,
            'max_length' => 100,
            'regex' => null,
            'help_text' => '',
            'placeholder' => '',
            'pair_label_left' => '',
            'pair_label_right' => '',
            'pair_placeholder_left' => '',
            'pair_placeholder_right' => '',
            'multi_text_items' => '',
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
