<?php
namespace Core;

class Validator
{
    /** @var array<string, string[]> */
    private array $errors = [];

    /**
     * フォーム項目定義に基づいて入力をバリデーション。
     *
     * @param array<int, array> $fields  form_fields の配列
     * @param array<string, mixed> $input
     * @param array<string, array> $files  $_FILES 形式（fieldKey => entry）
     * @return array{values: array<string,mixed>, errors: array<string,string[]>}
     */
    public function validate(array $fields, array $input, array $files = []): array
    {
        $values = [];
        foreach ($fields as $field) {
            $key = $field['field_key'];
            $type = $field['type'];

            if ($type === 'file') {
                $entry = $files[$key] ?? null;
                // ファイル項目は submission_values には保存しない（submission_files で管理）
                // テンプレート用に元ファイル名だけは values に残す
                $values[$key] = is_array($entry) && !empty($entry['name']) ? (string) $entry['name'] : '';
                $this->validateFile($field, $entry);
                continue;
            }

            $raw = $input[$key] ?? null;
            $clean = $this->normalize($raw, $type);
            $values[$key] = $clean;
            $this->validateScalar($field, $clean);
        }

        return ['values' => $values, 'errors' => $this->errors];
    }

    private function normalize($raw, string $type)
    {
        if (is_array($raw)) {
            $arr = [];
            foreach ($raw as $v) {
                if (is_string($v)) {
                    $arr[] = trim($v);
                }
            }
            return $arr;
        }
        if ($raw === null) {
            return $type === 'checkbox' ? [] : '';
        }
        if (is_string($raw)) {
            return trim($raw);
        }
        return '';
    }

    private function validateScalar(array $field, $value): void
    {
        $key   = $field['field_key'];
        $label = $field['label'];
        $type  = $field['type'];
        $req   = (int) ($field['required'] ?? 0) === 1;
        $maxL  = isset($field['max_length']) ? (int) $field['max_length'] : 0;
        $regex = $field['regex'] ?? '';
        $opts  = self::decodeOptions($field['options_json'] ?? null);

        $isEmpty = is_array($value) ? count($value) === 0 : ((string) $value === '');
        if ($req && $isEmpty) {
            $this->errors[$key][] = $label . 'は必須項目です。';
            return;
        }
        if ($isEmpty) {
            return;
        }

        if (is_string($value)) {
            if ($maxL > 0 && mb_strlen($value, 'UTF-8') > $maxL) {
                $this->errors[$key][] = $label . 'は' . $maxL . '文字以内で入力してください。';
            }
            if ($regex !== '' && @preg_match($regex, '') !== false) {
                if (!preg_match($regex, $value)) {
                    $this->errors[$key][] = $label . 'の形式が正しくありません。';
                }
            }
        }

        switch ($type) {
            case 'email':
                if (is_string($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$key][] = $label . 'はメールアドレスの形式で入力してください。';
                }
                break;
            case 'tel':
                if (is_string($value) && !preg_match('/\A[0-9+\-\s()]{6,20}\z/', $value)) {
                    $this->errors[$key][] = $label . 'は電話番号の形式で入力してください。';
                }
                break;
            case 'number':
                if (is_string($value) && !is_numeric($value)) {
                    $this->errors[$key][] = $label . 'は数値で入力してください。';
                }
                break;
            case 'date':
                if (is_string($value) && !$this->isValidDate($value)) {
                    $this->errors[$key][] = $label . 'は有効な日付（YYYY-MM-DD）で入力してください。';
                }
                break;
            case 'time':
                if (is_string($value) && !preg_match('/\A([01]\d|2[0-3]):[0-5]\d\z/', $value)) {
                    $this->errors[$key][] = $label . 'は有効な時刻（HH:MM）で入力してください。';
                }
                break;
            case 'radio':
            case 'select':
                if (is_string($value) && !in_array($value, $opts, true)) {
                    $this->errors[$key][] = $label . 'の値が不正です。';
                }
                break;
            case 'checkbox':
                if (is_array($value)) {
                    foreach ($value as $v) {
                        if (!in_array($v, $opts, true)) {
                            $this->errors[$key][] = $label . 'の値が不正です。';
                            break;
                        }
                    }
                }
                break;
        }
    }

    private function validateFile(array $field, $entry): void
    {
        $key   = $field['field_key'];
        $label = $field['label'];
        $req   = (int) ($field['required'] ?? 0) === 1;

        if (!$entry || !isset($entry['error']) || $entry['error'] === UPLOAD_ERR_NO_FILE) {
            if ($req) {
                $this->errors[$key][] = $label . 'は必須項目です。';
            }
            return;
        }

        if ($entry['error'] !== UPLOAD_ERR_OK) {
            $this->errors[$key][] = $label . 'のアップロードに失敗しました（コード:' . (int) $entry['error'] . '）';
            return;
        }

        $maxSize = (int) config('upload.max_size', 5 * 1024 * 1024);
        if (($entry['size'] ?? 0) > $maxSize) {
            $this->errors[$key][] = $label . 'のファイルサイズが大きすぎます（最大' . self::formatBytes($maxSize) . '）。';
            return;
        }

        $allowed = array_map('strtolower', (array) config('upload.allowed_extensions', []));
        $ext = strtolower(pathinfo($entry['name'] ?? '', PATHINFO_EXTENSION));
        if ($ext === '' || !in_array($ext, $allowed, true)) {
            $this->errors[$key][] = $label . 'のファイル形式が許可されていません。';
            return;
        }

        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $entry['tmp_name']);
            finfo_close($finfo);
            if ($mime === false) {
                $this->errors[$key][] = $label . 'のファイルが読み取れません。';
            }
        }
    }

    private function isValidDate(string $value): bool
    {
        $d = \DateTime::createFromFormat('Y-m-d', $value);
        return $d && $d->format('Y-m-d') === $value;
    }

    public static function decodeOptions($json): array
    {
        if (empty($json)) {
            return [];
        }
        $arr = json_decode($json, true);
        return is_array($arr) ? array_values(array_filter(array_map('strval', $arr), fn($v) => $v !== '')) : [];
    }

    public static function formatBytes(int $bytes): string
    {
        if ($bytes >= 1024 * 1024) return round($bytes / 1024 / 1024, 1) . 'MB';
        if ($bytes >= 1024)        return round($bytes / 1024, 1) . 'KB';
        return $bytes . 'B';
    }
}
